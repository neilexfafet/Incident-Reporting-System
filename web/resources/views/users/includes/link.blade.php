<meta charset="utf-8" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!-- GOOGLE FONTS -->
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
<link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />
<!-- PLUGINS CSS STYLE -->
<link href={{ asset("assets/plugins/nprogress/nprogress.css") }} rel="stylesheet" />
<!-- No Extra plugin used -->
<link href={{ asset("assets/plugins/jvectormap/jquery-jvectormap-2.0.3.css") }} rel="stylesheet" />
<link href={{ asset("assets/plugins/daterangepicker/daterangepicker.css") }} rel="stylesheet" />
<link href={{ asset("assets/plugins/toastr/toastr.css") }} rel="stylesheet" />
<link href={{ asset("assets/plugins/toastr/toastr.min.css") }} rel="stylesheet" />
<link href={{ asset("assets/plugins/ladda/ladda.min.css") }} rel="stylesheet" />
<!-- SLEEK CSS -->
<link id="sleek-css" rel="stylesheet" href={{ asset("assets/css/sleek.css") }} />
<!-- SUMMERNOTE -->
<link href={{ asset("assets/plugins/summernote/summernote.css") }} rel="stylesheet" />
<!-- FAVICON -->
<link href={{ asset("assets/img/pnpseal.png") }} rel="shortcut icon" />
<!--DATA TABLE -->
<link href={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.css") }} rel="stylesheet">
<link href={{ asset("assets/plugins/data-tables/responsive.datatables.min.css") }} rel="stylesheet">
<!-- NPROGRESS PLUGIN -->
<script src={{ asset("assets/plugins/nprogress/nprogress.js") }}></script>
    <style>
        .modal { z-index: 1001 !important;} 
        .modal-backdrop {z-index: 1000 !important;}
        .pac-container {z-index: 1055 !important;}
    </style>

