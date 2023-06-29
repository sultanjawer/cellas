@extends('layouts.admin')
@section('content')
{{-- @include('partials.breadcrumb') --}}
@include('partials.subheader')
<style>
	.error {
		color: #F00;
		background-color: #FFF;
	}
</style>
<div class="panel" >
	<div class="panel-hdr">
		<h2>

		</h2>
		<div class="panel-toolbar">
			@include('partials.globaltoolbar')
		</div>
	</div>
	<div class="panel-container">
		<form id="profileform" method="POST" action="{{ route('admin.profile.update', [auth()->user()->id]) }}" enctype="multipart/form-data">
			@csrf
			<div class="panel-content">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div name="panel-1" class="panel" data-title="Panel Data" data-intro="Panel ini berisi data-data" data-step="2">
									<div class="panel-hdr">
										<h2>
											Informasi Biodata <span class="fw-300"></span>
										</h2>
									</div>
									<div class="panel-container show">
										<div class="panel-content">
											<div class="form-group row">
												<div class="col-md-6">
													<label class="form-label" for="name">Nama Lengkap <span class="text-danger">*</span></label>
													<input type="text" id="name" name="name"  class="form-control" placeholder="Nama Lengkap" value="{{ ($data_user->name??'') }}" required>
												</div>
												<div class="col-md-6">
													<label class="form-label" for="email">Email <span class="text-danger">*</span></label>
													<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ auth()->user()->email }}" required autocomplete="email">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label class="form-label" for="mobile_phone">No. Handphone <span class="text-danger">*</span></label>
													<input type="text" name="mobile_phone" class="form-control" placeholder="No. Handphone" value="{{ ($data_user->mobile_phone??'') }}" required>
													<div class="help-block">Jangan menggunakan no. pribadi.</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div name="panel-2" class="panel" data-title="Panel Data" data-intro="Panel ini berisi data-data" data-step="2">
									<div class="panel-hdr">
										<h2>
											Informasi Perusahaan <span class="fw-300"></span>
										</h2>

									</div>
									<div class="panel-container show row">
										<div class="col-md-3">
											<div class="panel-container show">
												<div class="panel-content">
													<div class="d-flex flex-column align-items-center justify-content-center">
														<div class="d-flex flex-column align-items-center justify-content-center">
															<img id="imgavatar" src="{{ asset(($data_user->avatar??'img/avatars/user.png')) }}" class="img-thumbnail rounded-circle shadow-2" alt="" style="width: 90px; height: 90px">
															<h5 class="mb-0 fw-700 text-center mt-3 mb-3">
																Foto Anda
															</h5>
														</div>
														<div class="form-group">
															<label class="form-label" for="firstname">Ganti foto</label>
															<div class="custom-file">
																<input type="file" class="custom-file-input" name="avatar" aria-describedby="avatar" onchange="readURL(this,1);">
																<label class="custom-file-label" for="avatar"></label>
															</div>
															<span class="help-block">Klik browse untuk memilih file</span>
														</div>
													</div>
												</div>

												<div class="panel-content">
													<div class="d-flex flex-column align-items-center justify-content-center">
														<div class="d-flex flex-column align-items-center justify-content-center">
															<img id="imglogo" src="{{ asset(($data_user->logo??'img/avatars/farmer.png')) }}" class="img-thumbnail rounded-circle shadow-2" alt="" style="width: 90px; height: 90px">
															<h5 class="mb-0 fw-700 text-center mt-3 mb-3">
																Logo Perusahaan
															</h5>
														</div>
														<div class="form-group">
															<label class="form-label" for="firstname">Ganti Logo Perusahaan</label>
															<div class="custom-file">
																<input type="file" class="custom-file-input" name="logo" aria-describedby="logo" onchange="readURL(this,2);">
																<label class="custom-file-label" for="logo"></label>
															</div>
															<span class="help-block">Klik browse untuk mengganti logo</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row no-gutters">
				<div class="col-md-1 ml-auto mr-3 text-right">
					<button  type="submit" class="btn btn-block btn-danger btn-xm  mb-3 mr-2">SIMPAN</button>
				</div>
			</div>
		</form>
	</div>
</div>


@endsection

@section('scripts')
@parent
<script src="{{ asset('js/jquery/jquery.validate.js') }}"></script>
<script src="{{ asset('js/jquery/additional-methods.js') }}"></script>
<script src="{{ asset('js/formplugins/inputmask/inputmask.bundle.js') }}"></script>


	<script>
		$(document).ready(function() {
			$(":input").inputmask();
			$('.npwp_company').mask('00.000.000.0-000.000');
			$('.nib_company').mask('0000000000000');
			$('.kodepos').mask('00000');
			$('.ktp').mask('0000000000000000');
			var $validator = $("#profileform").validate({
				rules: {
					name: {
						required: true
					},
					email: {
						required: true,
					},
					mobile_phone: {
						required: true,
						minlength: 10
					},
					ktp: {
						required: true,
						minlength: 16
					},
					company_name: {
						required: true
					},
					pic_name: {
						required: true
					},
					jabatan: {
						required: true
					},
					npwp_company: {
						required: true,
						minlength: 15
					},
					nib_company: {
						required: true,
						minlength: 13
					},
					address_company: {
						required: true
					},
					provinsi: {
						required: true
					},
					kabupaten: {
						required: true
					},
					kecamatan: {
						required: true
					},
					desa: {
						required: true
					},
					kodepos: {
						required: true
					},
					username: {
						required: true,
						minlength: 3
					},
					password: {
						required: true,
						minlength: 6
					},
					password_confirmation: {
						required: true,
						minlength: 6
					},

					dataok: {
						required: true

					},
					terms: {
						required: true

					}
				},
				messages:{
					name:
					{
						required:"Nama harus diisi"
					},
					email:
					{
						required:"Email harus diisi",
						email: "Format Email tidak benar"
					},
					mobile_phone:
					{
						required:"No handphone harus diisi",
						minlength: "minimal {0} digit"
					},
					ktp:
					{
						required:"No KTP harus diisi",
						minlength: "minimal {0} digit"
					},
					company_name:
					{
						required:"Nama perusahaan harus diisi"

					},
					pic_name:
					{
						required:"Nama penanggung jawab harus diisi"
					},
					jabatan:
					{
						required:"Jabatan harus diisi"
					},
					npwp_company: {
						required: "NPWP perusahaan harus diisi",
						minlength: "minimal {0} digit"
					},
					nib_company: {
						required: "NIB perusahaan harus diisi",
						minlength: "minimal {0} digit"
					},
					address_company: {
						required: "Alamat perusahaan harus diisi"
					},
					provinsi: {
						required: "Pilih provinsi"
					},
					kabupaten: {
						required: "Pilih kabupaten"
					},
					kecamatan: {
						required: "Pilih kecamatan"
					},
					desa: {
						required: "Pilih Desa / Kelurahan"
					},
					kodepos: {
						required: "Kode Pos harus diisi"
					},
					username: {
						required: "Username harus diisi",
						minlength: "minimal {0} karakter"
					},
					password: {
						required: "Password harus diisi",
						minlength: "minimal {0} karakter"
					},
					password_confirmation: {
						required: "Password belum dikonfirmmasi",
						minlength: "minimal {0} karakter"
					},

					dataok: {
						required: "!"
					},
					terms: {
						required: "!"

					}
				}
			});

			$('#province').on('change', function() {
				var province_id =$(this).val();
				$.ajax({
					type: 'get',
					url: '/api/getAPIKabupatenProp',
					data: {'provinsi':province_id},
					success: function(data){
						$('#kabupaten').find('option').remove().end();
						$('#kecamatan').find('option').remove().end();
						$('#desa').find('option').remove().end();

						for (var i = 0; i < data.data.length; i++){

							$('#kabupaten')
							.find('option')
							.end()
							.append('<option value="'+data.data[i].kd_kab+'">'+data.data[i].nama_kab+'</option>');
						}
						$('#kabupaten').trigger("change");
					},
					error: function(){
						console.log('error load kabupaten');
					},
				});
			});
			$('#kabupaten').on('change', function() {
				var kab_id =$(this).val();
				$.ajax({
					type: 'get',
					url: '/api/getAPIKecamatanKab',
					data: {'kabupaten':kab_id},
					success: function(data){
						$('#kecamatan').find('option').remove().end();
						$('#desa').find('option').remove().end();
						for (var i = 0; i < data.data.length; i++){
							$('#kecamatan')
							.find('option')
							.end()
							.append('<option value="'+data.data[i].kd_kec+'">'+data.data[i].nm_kec+'</option>');
						}
						$('#kecamatan').trigger("change");
					},
					error: function(){
						console.log('error load kecamatan');
					},
				});
			});
			$('#kecamatan').on('change', function() {
				var kec_id =$(this).val();
				$.ajax({
					type: 'get',
					url: '/api/getAPIDesaKec',
					data: {'kecamatan':kec_id},
					success: function(data){
						$('#desa').find('option').remove().end();
						for (var i = 0; i < data.data.length; i++){
							$('#desa')
							.find('option')
							.end()
							.append('<option value="'+data.data[i].kd_desa+'">'+data.data[i].nm_desa+'</option>');
						}
					},
					error: function(){
						console.log('error load desa');
					},
				});
			});


			$(".select2-prov").select2({
				placeholder: "Select Province"
			});
			$(".select2-kab").select2({
				placeholder: "Select Kabupaten"
			});
			$(".select2-kec").select2({
				placeholder: "Select Kecamatan"
			});
			$(".select2-des").select2({
				placeholder: "Select Desa"
			});



		});

	</script>


	<script>
			function readURL(input, id) {
				if (input.files && input.files[0]) {
					var reader = new FileReader();

					reader.onload = function (e) {
						if (id == 1){
							$('#imgavatar')
								.attr('src', e.target.result)
								.width(90)
								.height(90);
						}
						if (id == 2){
							$('#imglogo')
								.attr('src', e.target.result)
								.width(90)
								.height(90);
						}

					};

					reader.readAsDataURL(input.files[0]);
				}
			}

	</script>
@endsection
