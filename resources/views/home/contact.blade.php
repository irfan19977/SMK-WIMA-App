@extends('home.layouts.app')

@section('content')
        <!-- Start Section Banner Area -->
        <div class="section-banner bg-4">
            <div class="container">
                <div class="banner-spacing">
                    <div class="section-info">
                        <h2 data-aos="fade-up" data-aos-delay="100">{{ __('home.contact_banner_title') }}</h2>
                        <p data-aos="fade-up" data-aos-delay="200">{{ __('home.contact_banner_description') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Section Banner Area -->
        
        <!-- Start Contact  Area-->
        <div class="contact-area ptb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="contact-content">
                            <div class="header-content">
                                <h2>{{ __('home.contact_header_title') }}</h2>
                                <p>{{ __('home.contact_header_description') }}</p>
                                <p>{{ __('home.contact_email_info') }} <a href="#">info@smkpgri-lawang.sch.id</a></p>
                            </div>

                            <div class="contact-form">
                                <form id="contactForm" onsubmit="event.preventDefault(); sendToWhatsApp();">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label for="name">{{ __('home.contact_form_first_name') }}</label>
                                                <input type="text" name="first_name" class="form-control" id="first_name" required data-error="{{ __('home.contact_form_error_first_name') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label for="name">{{ __('home.contact_form_last_name') }}</label>
                                                <input type="text" name="last_name" class="form-control" id="last_name" required data-error="{{ __('home.contact_form_error_last_name') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                            
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label for="name">{{ __('home.contact_form_email') }}</label>
                                                <input type="email" name="email" class="form-control" id="email" required data-error="{{ __('home.contact_form_error_email') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label for="name">{{ __('home.contact_form_phone') }}</label>
                                                <input type="text" name="phone_number" class="form-control" id="phone_number" required data-error="{{ __('home.contact_form_error_phone') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
    
                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group">
                                                <label for="name">{{ __('home.contact_form_inquiry_type') }}</label>
                                                <select class="form-select" name="inquiry_type" aria-label="Default select example">
                                                    <option value="Pendaftaran" selected>{{ __('home.contact_info_registration') }}</option>
                                                    <option value="Kerja Sama">{{ __('home.contact_info_general') }}</option>
                                                    <option value="Administrasi">Administrasi</option>
                                                    <option value="Beasiswa">Beasiswa</option>
                                                    <option value="Bantuan Teknis">Bantuan Teknis</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group">
                                                <label for="name">{{ __('home.contact_form_message') }}</label>
                                                <textarea name="message" id="message" class="form-control" cols="30" rows="6" required data-error="{{ __('home.contact_form_error_message') }}" placeholder=""></textarea>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
    
                                        <div class="col-lg-12 col-md-12">
                                            <button type="submit" class="default-btn">{{ __('home.contact_form_submit') }}</button>
                                            <div id="msgSubmit" class="h3 text-center hidden"></div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="contact-info">

                         <!-- Start Map Area -->
                        <div id="map" class="map-pd">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.4735592932257!2d112.69365997517743!3d-7.845402792176032!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd62b0037e6cf2d%3A0x87e970cd3f7ba087!2sSMK%20PGRI%20Lawang!5e0!3m2!1sid!2sid!4v1764651313640!5m2!1sid!2sid"></iframe>
                        </div>
                        <!-- End Map Area -->

                        <div class="info-details">
                            <ul>
                                <li><i class='bx bxs-phone-call'></i> {{ __('home.contact_info_general') }} - <a href="tel:+6282233088346">+62 822-3308-8346</a></li>
                                <li><i class='bx bxs-phone-call'></i> {{ __('home.contact_info_registration') }} - <a href="tel:+6282233088346">+62 822-3308-8346</a></li>
                                <li><i class='bx bxs-phone-call'></i> {{ __('home.contact_info_majors') }} - <a href="tel:+6282233088346">+62 822-3308-8346</a></li>
                                <li><i class='bx bxs-phone-call'></i> WhatsApp - <a href="https://wa.me/6282233088346">+62 822-3308-8346</a></li>
                                <li><i class='bx bxs-map'></i> Jl. DR. Wahidin No.17, Krajan, Kalirejo, Lawang, Kab. Malang</li>
                                <li><i class='bx bxs-envelope'></i><a class="info-mail" href="mailto:info@smkpgri-lawang.sch.id">info@smkpgri-lawang.sch.id</a></li>
                            </ul>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script>
  function sendToWhatsApp() {
    const form = document.getElementById('contactForm');
    const firstName = form.elements['first_name'].value || '-';
    const lastName = form.elements['last_name'].value || '-';
    const email = form.elements['email'].value || '-';
    const phone_number = form.elements['phone_number'].value || '-';
    const inquiryType = form.elements['inquiry_type'].value || '-';
    const message = form.elements['message'].value || '-';

    // Tentukan nomor WhatsApp berdasarkan jenis pertanyaan
    let phone;
    switch(inquiryType) {
      case 'Pendaftaran':
      case 'Bantuan Teknis':
        phone = '6285802733781'; // Nomor khusus pendaftaran dan bantuan teknis
        break;
      case 'Kerja Sama':
      case 'Beasiswa':
        phone = '6285102321546'; // Nomor khusus kerja sama dan beasiswa (ganti dengan nomor yang diinginkan)
        break;
      case 'Administrasi':
      case 'Lainnya':
      default:
        phone = '6281336526350'; // Nomor umum
        break;
    }

    const text =
      'Halo SMK PGRI Lawang,%0A' +
      'Saya memiliki pertanyaan melalui form kontak website.%0A%0A' +
      'Nama: ' + encodeURIComponent(firstName + ' ' + lastName) + '%0A' +
      'Email: ' + encodeURIComponent(email) + '%0A' +
      'Telepon: ' + encodeURIComponent(phone_number) + '%0A' +
      'Jenis Pertanyaan: ' + encodeURIComponent(inquiryType) + '%0A%0A' +
      'Pesan:%0A' + encodeURIComponent(message);

    const url = `https://wa.me/${phone}?text=${text}`;
    window.open(url, '_blank');
  }
</script>
@endpush