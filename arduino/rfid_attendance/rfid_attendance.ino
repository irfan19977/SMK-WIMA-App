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
#define LED_MERAH D0

// ===== KONFIGURASI WIFI & SERVER =====
const char* WIFI_SSID     = "Hekel";
const char* WIFI_PASSWORD = "27Mei1997";
const char* SERVER_URL    = "http://10.19.203.201:8000/api/rfid/detect";

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
  pinMode(LED_MERAH, OUTPUT);
  digitalWrite(BUZZER, LOW);
  digitalWrite(LED_HIJAU, LOW);
  digitalWrite(LED_MERAH, LOW);

  Serial.println("\n============================");
  Serial.println("   ESP8266 RFID System");
  Serial.println("============================");

  hubungkanWiFi();
  sinkronisasiWaktu();

  // LED Hijau menyala solid = sistem siap
  digitalWrite(LED_HIJAU, HIGH);

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
  int statusKirim = kirimKeServer(uid, waktuTapEpoch, responMs, waktuSimpanStr);

  if (statusKirim == 1) {
    Serial.println("[OK  ] Data berhasil dikirim ke server!");
    Serial.print("[TIME] Respon: ");
    Serial.print(responMs / 1000.0, 3);
    Serial.println(" detik");
    Serial.println("[SAVE] Tersimpan: " + waktuSimpanStr);
    berhasil();
  } else if (statusKirim == 2) {
    Serial.println("[INFO] Siswa sudah absen sebelumnya, tidak direcord ulang.");
    sudahAbsen();
  } else {
    Serial.println("[FAIL] Gagal mengirim data ke server!");
    gagal();
  }

  Serial.println("----------------------------\n");

  rfid.PICC_HaltA();
  rfid.PCD_StopCrypto1();
}

// ===== KONEKSI WIFI =====
void hubungkanWiFi() {
  Serial.print("[WIFI] Menghubungkan ke: ");
  Serial.println(WIFI_SSID);

  // LED Merah saat proses koneksi
  digitalWrite(LED_HIJAU, LOW);
  digitalWrite(LED_MERAH, HIGH);

  // Set WiFi mode untuk stability
  WiFi.mode(WIFI_STA);
  WiFi.setSleepMode(WIFI_NONE_SLEEP); // Matikan sleep mode untuk koneksi stabil
  WiFi.persistent(true);

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

  // WiFi terhubung: matikan merah
  digitalWrite(LED_MERAH, LOW);

  Serial.println("\n[OK  ] WiFi Terhubung!");
  Serial.print("[IP  ] ");
  Serial.println(WiFi.localIP());
  Serial.print("[RSSI] Signal strength: ");
  Serial.print(WiFi.RSSI());
  Serial.println(" dBm");

  // Tunggu WiFi stabil sebelum NTP
  delay(2000);
}

// ===== SINKRONISASI WAKTU NTP =====
void sinkronisasiWaktu() {
  Serial.print("[NTP ] Sinkronisasi waktu ke Asia pool...");
  configTime(GMT_OFFSET, DST_OFFSET, "asia.pool.ntp.org");

  // Tunggu sampai waktu valid (bukan epoch 0)
  int percobaan = 0;
  while (time(nullptr) < 1000000000UL) {
    delay(500);
    Serial.print(".");
    percobaan++;
    if (percobaan > 30) {
      Serial.println("\n[WARN] NTP gagal, waktu mungkin tidak akurat!");
      Serial.println("[INFO] Sistem tetap berjalan dengan waktu lokal.");
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
// Mengembalikan: 1=berhasil, 2=duplicate/sudah absen, 0=gagal
int kirimKeServer(String uid, unsigned long waktuTap,
                  unsigned long &responMs, String &waktuSimpanStr) {
  responMs       = 0;
  waktuSimpanStr = "-";

  // Cek koneksi WiFi
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("[WARN] WiFi terputus! Mencoba reconnect...");
    hubungkanWiFi();
    return 0;
  }

  // Retry mechanism (maks 2x untuk kecepatan)
  const int MAX_RETRY = 2;
  int httpCode = -1;

  for (int retry = 1; retry <= MAX_RETRY; retry++) {
    WiFiClient client;
    HTTPClient http;

    http.begin(client, SERVER_URL);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("Accept", "application/json");
    http.setTimeout(3000); // 3 detik timeout untuk fast fail

    // Body JSON dengan waktu tap
    StaticJsonDocument<200> doc;
    doc["uid"]       = uid;
    doc["waktu_tap"] = epochKeString(waktuTap);
    String body;
    serializeJson(doc, body);

    if (retry == 1) {
      Serial.println("[HTTP] Mengirim ke server...");
      Serial.println("[BODY] " + body);
    } else {
      Serial.println("[RETRY] Percobaan ke-" + String(retry) + "...");
    }

    // Ukur waktu respon
    unsigned long mulai = millis();
    httpCode = http.POST(body);
    responMs = millis() - mulai;

    Serial.println("[HTTP] Code: " + String(httpCode));

    // Jika sukses atau duplicate, keluar dari loop
    if (httpCode > 0) {
      String response = http.getString();
      Serial.println("[RESP] " + response);

      // Ambil waktu_simpan dari respons JSON server (opsional)
      StaticJsonDocument<256> resDoc;
      DeserializationError err = deserializeJson(resDoc, response);
      if (!err && resDoc.containsKey("waktu_simpan")) {
        waktuSimpanStr = resDoc["waktu_simpan"].as<String>();
      } else {
        waktuSimpanStr = epochKeString(time(nullptr));
      }

      http.end();
      break;
    } else {
      // Error negatif = connection issue
      Serial.println("[ERR ] Connection error: " + String(httpCode));
      if (httpCode == -1) Serial.println("      -> Connection refused");
      if (httpCode == -11) Serial.println("      -> DNS/Timeout/Server unreachable");
      http.end();

      if (retry < MAX_RETRY) {
        delay(500); // tunggu 0.5 detik sebelum retry
      }
    }
  }

  if (httpCode == 200 || httpCode == 201) return 1;
  if (httpCode == 409) return 2; // duplicate / sudah absen
  return 0;
}

// ===== EFEK BERHASIL (absen baru) =====
void berhasil() {
  digitalWrite(BUZZER, HIGH); delay(100);
  digitalWrite(BUZZER, LOW);  delay(100);
  digitalWrite(BUZZER, HIGH); delay(100);
  digitalWrite(BUZZER, LOW);

  // Kedip 2x cepat
  for (int i = 0; i < 2; i++) {
    digitalWrite(LED_HIJAU, LOW);
    delay(150);
    digitalWrite(LED_HIJAU, HIGH);
    delay(150);
  }
}

// ===== EFEK SUDAH ABSEN (duplicate) =====
void sudahAbsen() {
  digitalWrite(BUZZER, HIGH); delay(200);
  digitalWrite(BUZZER, LOW);

  // Kedip 1x lambat
  digitalWrite(LED_HIJAU, LOW);
  delay(400);
  digitalWrite(LED_HIJAU, HIGH);
}

// ===== EFEK GAGAL (WiFi/server error) =====
void gagal() {
  digitalWrite(LED_HIJAU, LOW); // matikan hijau dulu

  // LED Merah kedip 2x + bunyi panjang
  for (int i = 0; i < 2; i++) {
    digitalWrite(LED_MERAH, HIGH);
    digitalWrite(BUZZER, HIGH); delay(300);
    digitalWrite(LED_MERAH, LOW);
    digitalWrite(BUZZER, LOW);  delay(200);
  }

  digitalWrite(LED_HIJAU, HIGH); // kembalikan hijau (sistem masih siap)
}
