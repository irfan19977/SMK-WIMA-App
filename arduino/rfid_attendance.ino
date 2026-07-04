#include <SPI.h>
#include <MFRC522.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <ArduinoJson.h>
#include <time.h>

// ===== KONFIGURASI PIN =====
#define SS_PIN    D4
#define RST_PIN   D3
#define BUZZER    D2
#define LED_HIJAU D1

// ===== KONFIGURASI WIFI & SERVER =====
const char* WIFI_SSID     = "MyHome";
const char* WIFI_PASSWORD = "88888888";
const char* SERVER_URL    = "http://192.168.1.7:8000/api/rfid/detect";

// ===== KONFIGURASI NTP =====
const char* NTP_SERVER    = "pool.ntp.org";
const long  GMT_OFFSET    = 7 * 3600; // WIB = UTC+7
const int   DST_OFFSET    = 0;

MFRC522 rfid(SS_PIN, RST_PIN);

// ===== SETUP =====
void setup() {
  Serial.begin(115200);
  SPI.begin();
  rfid.PCD_Init();

  pinMode(BUZZER, OUTPUT);
  pinMode(LED_HIJAU, OUTPUT);
  digitalWrite(BUZZER, LOW);
  digitalWrite(LED_HIJAU, LOW);

  Serial.println("\n============================");
  Serial.println("   ESP8266 RFID System");
  Serial.println("============================");

  hubungkanWiFi();
  sinkronisasiWaktu();

  Serial.println("[OK] Sistem siap. Tempelkan kartu RFID...");
  Serial.println("============================\n");
}

// ===== LOOP =====
void loop() {
  if (!rfid.PICC_IsNewCardPresent()) return;
  if (!rfid.PICC_ReadCardSerial()) return;

  // Catat waktu tap (epoch & string)
  unsigned long waktuTapEpoch = time(nullptr);
  String waktuTapStr = epochKeString(waktuTapEpoch);

  String uid = bacaUID();
  Serial.println("----------------------------");
  Serial.println("[INFO] Kartu terdeteksi!");
  Serial.println("[UID ] " + uid);
  Serial.println("[TAP ] " + waktuTapStr);

  unsigned long responMs;
  String waktuSimpanStr;
  bool sukses = kirimKeServer(uid, waktuTapEpoch, responMs, waktuSimpanStr);

  if (sukses) {
    Serial.println("[OK  ] Data berhasil dikirim ke server!");
    Serial.print("[TIME] Respon: ");
    Serial.print(responMs / 1000.0, 3);
    Serial.println(" detik");
    Serial.println("[SAVE] Tersimpan: " + waktuSimpanStr);
    berhasil();
  } else {
    Serial.println("[FAIL] Gagal mengirim data ke server!");
  }

  Serial.println("----------------------------\n");

  rfid.PICC_HaltA();
  rfid.PCD_StopCrypto1();
}

// ===== KONEKSI WIFI =====
void hubungkanWiFi() {
  Serial.print("[WIFI] Menghubungkan ke: ");
  Serial.println(WIFI_SSID);

  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);

  int percobaan = 0;
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
    percobaan++;
    if (percobaan > 20) {
      Serial.println("\n[WARN] Gagal konek WiFi, restart...");
      ESP.restart();
    }
  }

  Serial.println("\n[OK  ] WiFi Terhubung!");
  Serial.print("[IP  ] ");
  Serial.println(WiFi.localIP());
}

// ===== SINKRONISASI WAKTU NTP =====
void sinkronisasiWaktu() {
  Serial.print("[NTP ] Sinkronisasi waktu...");
  configTime(GMT_OFFSET, DST_OFFSET, NTP_SERVER);

  // Tunggu sampai waktu valid (bukan epoch 0)
  int percobaan = 0;
  while (time(nullptr) < 1000000000UL) {
    delay(500);
    Serial.print(".");
    percobaan++;
    if (percobaan > 20) {
      Serial.println("\n[WARN] NTP gagal, waktu mungkin tidak akurat!");
      return;
    }
  }
  Serial.println("\n[OK  ] Waktu tersinkron: " + epochKeString(time(nullptr)));
}

// ===== KONVERSI EPOCH KE STRING WAKTU =====
String epochKeString(unsigned long epoch) {
  time_t t = (time_t)epoch;
  struct tm* tmInfo = localtime(&t);
  char buffer[25];
  strftime(buffer, sizeof(buffer), "%Y-%m-%d %H:%M:%S", tmInfo);
  return String(buffer);
}

// ===== BACA UID KARTU =====
String bacaUID() {
  String uid = "";
  for (byte i = 0; i < rfid.uid.size; i++) {
    if (rfid.uid.uidByte[i] < 0x10) uid += "0";
    uid += String(rfid.uid.uidByte[i], HEX);
    if (i < rfid.uid.size - 1) uid += ":";
  }
  uid.toUpperCase();
  return uid;
}

// ===== KIRIM DATA KE SERVER (POST JSON) =====
// Mengembalikan: sukses/tidak, durasi respon (ms), waktu simpan dari server
bool kirimKeServer(String uid, unsigned long waktuTap,
                   unsigned long &responMs, String &waktuSimpanStr) {
  responMs       = 0;
  waktuSimpanStr = "-";

  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("[WARN] WiFi terputus! Mencoba reconnect...");
    hubungkanWiFi();
    return false;
  }

  WiFiClient client;
  HTTPClient http;

  http.begin(client, SERVER_URL);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Accept", "application/json");

  // Body JSON dengan waktu tap
  StaticJsonDocument<200> doc;
  doc["uid"]       = uid;
  doc["waktu_tap"] = epochKeString(waktuTap);
  String body;
  serializeJson(doc, body);

  Serial.println("[HTTP] Mengirim ke server...");
  Serial.println("[BODY] " + body);

  // Ukur waktu respon
  unsigned long mulai = millis();
  int httpCode = http.POST(body);
  responMs = millis() - mulai;

  Serial.println("[HTTP] Code: " + String(httpCode));

  if (httpCode > 0) {
    String response = http.getString();
    Serial.println("[RESP] " + response);

    // Ambil waktu_simpan dari respons JSON server (opsional)
    // Server diharapkan mengembalikan: {"waktu_simpan": "2025-01-01 12:00:01", ...}
    StaticJsonDocument<256> resDoc;
    DeserializationError err = deserializeJson(resDoc, response);
    if (!err && resDoc.containsKey("waktu_simpan")) {
      waktuSimpanStr = resDoc["waktu_simpan"].as<String>();
    } else {
      // Fallback: gunakan waktu lokal saat respon diterima
      waktuSimpanStr = epochKeString(time(nullptr));
    }
  }

  http.end();
  return (httpCode == 200 || httpCode == 201);
}

// ===== EFEK BERHASIL =====
void berhasil() {
  digitalWrite(BUZZER, HIGH); delay(100);
  digitalWrite(BUZZER, LOW);  delay(100);
  digitalWrite(BUZZER, HIGH); delay(100);
  digitalWrite(BUZZER, LOW);

  digitalWrite(LED_HIJAU, HIGH);
  delay(500);
  digitalWrite(LED_HIJAU, LOW);
}
