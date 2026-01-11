@extends('home.layouts.app')

@section('content')
    <div class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('frontend/images/bg.png') }}'); background-attachment:fixed;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-8 ftco-animate text-center">
            <p class="breadcrumbs">
              <span class="mr-2"><a href="{{ route('/') }}">Home</a></span>
              <span>Kontak</span>
            </p>
            <h1 class="mb-3 bread">Hubungi Kami</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section contact-section ftco-degree-bg">
      <div class="container">
        <div class="row d-flex mb-5 contact-info">
          <div class="col-md-12 mb-4">
            <h2 class="h4">Informasi Kontak</h2>
          </div>
          <div class="w-100"></div>
          <div class="col-md-3">
            <p><span>Alamat:</span> Jl. DR. Wahidin No.17, Krajan, Kalirejo, Lawang, Kab. Malang</p>
          </div>
          <div class="col-md-3">
            <p><span>Telepon / WA:</span> <a href="tel://6282233088346">+62 822-3308-8346</a></p>
          </div>
          <div class="col-md-3">
            <p><span>Email:</span> <a href="mailto:info@smkpgri-lawang.sch.id">www.cssmkpgrilwg@gmail.com</a></p>
          </div>
          <div class="col-md-3">
            <p><span>Website</span> <a href="#">smkpgri-lawang.sch.id</a></p>
          </div>
        </div>
        <div class="row block-9">
          <div class="col-md-6 pr-md-5">
            <h4 class="mb-4">Ada pertanyaan? Kirim pesan ke kami</h4>
            <p class="mb-3">Silakan isi formulir berikut. Saat menekan tombol, Anda akan diarahkan ke WhatsApp resmi sekolah dengan pesan otomatis.</p>
            <form id="waContactForm" onsubmit="event.preventDefault(); sendToWhatsApp();">
              <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Nama Anda" required>
              </div>
              <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email Anda (opsional)">
              </div>
              <div class="form-group">
                <input type="text" name="subject" class="form-control" placeholder="Subjek" required>
              </div>
              <div class="form-group">
                <textarea name="message" id="message" cols="30" rows="7" class="form-control" placeholder="Pesan" required></textarea>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-success py-3 px-5">Kirim via WhatsApp</button>
              </div>
            </form>
          
          </div>

          <div class="col-md-6">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.4735592932257!2d112.69365997517743!3d-7.845402792176032!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd62b0037e6cf2d%3A0x87e970cd3f7ba087!2sSMK%20PGRI%20Lawang!5e0!3m2!1sid!2sid!4v1764651313640!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </section>
		
		<section class="ftco-section-parallax">
      <div class="parallax-img d-flex align-items-center">
        <div class="container">
          <div class="row d-flex justify-content-center">
            <div class="col-md-7 text-center heading-section heading-section-white ftco-animate">
              <h2>Berlangganan Newsletter Kami</h2>
              <p>Jauh di sana, di balik pegunungan kata-kata, jauh dari negara-negara Vokalia dan Konsonantia, hiduplah teks-teks buta. Terpisah mereka hidup di</p>
              <div class="row d-flex justify-content-center mt-5">
                <div class="col-md-8">
                  <form action="#" class="subscribe-form">
                    <div class="form-group d-flex">
                      <input type="text" class="form-control" placeholder="Masukkan alamat email">
                      <input type="submit" value="Berlangganan" class="submit px-3">
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection

@push('scripts')
<script>
  function sendToWhatsApp() {
    const phone = '6282233088346'; // ganti dengan nomor WhatsApp sekolah
    const form = document.getElementById('waContactForm');
    const name = form.elements['name'].value || '-';
    const email = form.elements['email'].value || '-';
    const subject = form.elements['subject'].value || '-';
    const message = form.elements['message'].value || '-';

    const text =
      'Halo SMK PGRI Lawang,%0A' +
      'Nama: ' + encodeURIComponent(name) + '%0A' +
      'Email: ' + encodeURIComponent(email) + '%0A' +
      'Subjek: ' + encodeURIComponent(subject) + '%0A%0A' +
      'Pesan:%0A' + encodeURIComponent(message);

    const url = `https://wa.me/${phone}?text=${text}`;
    window.open(url, '_blank');
  }
</script>
@endpush