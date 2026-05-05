<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Dashboard | RS Property</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">


    <script src="<?php echo base_url() ?>/admin/assets/libs/jquery/jquery.min.js"></script>
    <!-- DataTables -->
    <link href="<?php echo base_url() ?>/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="<?php echo base_url() ?>/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="<?php echo base_url() ?>/admin/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo base_url() ?>/admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <?php if (isset($summernote) && ($summernote == 1)) { ?>
        <!-- include summernote css/js -->
        <link href="<?php echo base_url() ?>/admin/assets/summernote/summernote-bs4.min.css" rel="stylesheet">
        <script src="<?php echo base_url() ?>/admin/assets/summernote/summernote-bs4.min.js"></script>
    <?php } ?>

    <?php if (isset($select2) && ($select2 == 2)) { ?>
        <!-- Select 2 -->
        <link href="<?php echo base_url() ?>/admin/assets/select2/css/select2.min.css" rel="stylesheet">
        <script src="<?php echo base_url() ?>/admin/assets/select2/js/select2.min.js"></script>
    <?php } ?>

    <!-- Jquery Date Picker -->
    <link href="<?php echo base_url() ?>/admin/assets/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <script src="<?php echo base_url() ?>/admin/assets/jquery-ui/jquery-ui.min.js"></script>

    <!-- App Css-->
    <link href="<?php echo base_url() ?>/admin/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>/admin/assets/css/custom.css" id="app-style" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url() ?>/admin/assets/libs/apexcharts/apexcharts.min.js"></script>

</head>

<!-- <body data-sidebar="dark"> -->

<body>

    <div id="layout-wrapper">
        <div class="page-content">

            <div class="container-fluid">

                <?php
                if (session()->getFlashdata('success')) {
                    echo "<h4 style='color:green;text-align:center;'>" . session()->getFlashdata('success') . "</h4>";
                }
                ?>

                <div class="row justify-content-center">

                    <div class="col-12 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                             <h4 class="card-title mb-4"> Your Package Details</h4>
                             <table id="" class="table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th scope="col">Package Name :</th>
                                    <td scope="col"><?= $package['pakage_title'] ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Duration :</th>
                                    <td scope="col"><?php
                                    
                                    
                                    if ($package['duration'] != 'other') {
                                                        if ($package['duration'] != 1) {
                                                            echo $package['duration'] . " days";
                                                        }
                                                    } else {
                                                        if ($package['d_m_y'] = 1) {
                                                            echo $package['how_many'] . "Days";
                                                        }
                                                        if ($package['d_m_y'] = 2) {
                                                            echo $package['how_many'] . "Days";
                                                        }
                                                        if ($package['d_m_y'] = 3) {
                                                            echo $package['how_many'] . "Days";
                                                        }
                                                    } ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Employee Limit :</th>
                                    <td scope="col"><?= $package['employee_limit'] ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">property Limit :</th>
                                    <td scope="col"><?= $package['property_limit'] ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Pakage Objective :</th>
                                    <td scope="col"><?= $package['pakage_objective'] ?></td>
                                </tr>
                                <tr>
                                    <th scope="col"></th>
                                    <td scope="col"><a href="<?php if($user['type']=1){
                                        echo base_url('/admin/poperty_list/'.$user['type']);}else{
                                            base_url('/admin/account_mode');
                                        }  ?>">Click Here</a> To Continue</td>
                                </tr>
                            </thead>

                        </table>
                            </div>
                        </div>
                       
                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->

    <script src="<?php echo base_url() ?>/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url() ?>/admin/assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="<?php echo base_url() ?>/admin/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="<?php echo base_url() ?>/admin/assets/libs/node-waves/waves.min.js"></script>
    <script src="<?php echo base_url() ?>/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url() ?>/admin/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- Responsive examples -->
    <script src="<?php echo base_url() ?>/admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url() ?>/admin/assets/js/custom.js"></script>
    <!-- Datatable init js -->
    <script src="<?php echo base_url() ?>/admin/assets/libs/tinymce/tinymce.min.js"></script>
    <!-- init js -->
    <script src="<?php echo base_url() ?>/admin/assets/js/pages/form-editor.init.js"></script>

    <script src="<?php echo base_url() ?>/admin/assets/js/pages/datatables.init.js"></script>

    <!-- apexcharts js -->



    <script src="<?php echo base_url() ?>/admin/assets/js/pages/form-validation.init.js"></script>

    <script src="<?php echo base_url() ?>/admin/assets/js/app.js"></script>


</body>

</html>