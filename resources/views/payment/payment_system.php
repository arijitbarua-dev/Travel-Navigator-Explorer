<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Package select | RS Property</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url() ?>/admin/assets/images/favicon.ico">

    <script src="<?php echo base_url() ?>/admin/assets/libs/jquery/jquery.min.js"></script>
    <link href="<?php echo base_url() ?>/admin/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo base_url() ?>/admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url() ?>/admin/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url() ?>/admin/assets/js/pages/form-validation.init.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,700,500&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Montserrat', sans-serif
    }

    body {
        
        padding: 10% 15% 10% 15%;
    }

    .wrapper {
        max-width: 460px;
        /* box-shadow: 3px 3px 5px #1b1b1ba2 azure */
    }

    .card {
        background-color: #ffffff;
    }

    p {
        margin: 0px
    }

    .h8 {
        font-size: 25px;
        font-weight: 600;
        color: white
    }

    .card .atm {
        width: 90px;
        height: 90px;
        object-fit: cover
    }

    .card .visa {
        width: 50px;
        height: 20px;
        object-fit: fill
    }

    .card .master {
        width: 50px;
        height: 50px;
        object-fit: fill
    }

    .debit-card {
        width: 100%;
        height: 180px;
        padding: 20px;
        background-color: #0093E9;
        background-image: linear-gradient(160deg, #0093E9 0%, #80D0C7 100%);
        position: relative;
        border-radius: 5px;
        box-shadow: 3px 3px 5px #0000001a;
        transition: all 0.3s ease-in;
        cursor: pointer
    }

    .debit-card:hover {
        box-shadow: 5px 3px 5px #000000a2
    }

    .card-2 {
        background-color: #075566;
        background-image: linear-gradient(116deg, #f1f5f4 0%, #ff2192 100%);
    }

    .text-muted {
        font-size: 0.8rem
    }

    .text-white {
        font-size: 14px
    }

    .input {
        position: absolute;
        top: 6px;
        right: 0
    }

    input[type="radio"] {
        appearance: none;
        width: 20px;
        height: 20px;
        background-color: #eee;
        position: relative;
        border-radius: 3px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        outline: none
    }

    input[type="radio"]:after {
        position: absolute;
        width: 100%;
        height: 100%;
        font-family: "Font Awesome 5 Free";
        font-weight: 600;
        content: "\f00c";
        color: #fff;
        font-size: 15px;
        display: none
    }

    input[type="radio"]:checked,
    input[type="radio"]:checked:hover {
        background-color: blue
    }

    input[type="radio"]:checked::after {
        display: flex;
        align-items: center;
        justify-content: center
    }

    input[type="radio"]:hover {
        background-color: #ddd
    }

    .btn {
        width: 100%;
        height: 50px;
        border: 1px solid #0093E9;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #0093E9;
        transition: all 0.5s ease;
        font-weight: 500
    }

    .btn:hover {
        color: #fff;
        background-color: #0093E9
    }
    </style>
   
</head>

<body>
<div class="Container">

        <div class="row">
          
           <div class="col-md-6 col-sm-6">
             <div class="debit-card mb-3">
              <div class="d-flex flex-column h-100">
                <label class="d-block">
                    <div class="d-flex position-relative">
                        <div> <img style="height: 100px;width:200px;" src="<?php echo base_url();?>/admin/assets/images/stripe.png" class="visa" alt="">

                        </div>
                        <div class="input"> <input type="radio" name="card" value="1" id="stripe"> </div>
                    </div>
                </label>
                <div class="mt-auto fw-bold d-flex align-items-center justify-content-between">
               
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-6">
            <div class="debit-card card-2 mb-4">
              <div class="d-flex flex-column h-100">
                <label class="d-block">
                    <div class="d-flex position-relative">
                        <div> <img style="height: 100px;width:150px;" src="<?php echo base_url();?>/admin/assets/images/paypal.png" alt="master" class="master">
                        </div>
                        <div class="input"> <input type="radio" name="card" value="2" id="paypal"> </div>
                    </div>
                </label>
                <div class="mt-auto fw-bold d-flex align-items-center justify-content-between">

                </div>
              </div>
             </div>
            </div>
           </div>

            <div class="invalid-feedback">
                 Please select Any One !!
            </div>

        
  
   
</div>

   <script>
    $(document).ready(function(){

       $('#stripe').click(function(){
         
         var package_id = "<?=$package['id']?>";
         var price = "<?=$package['price_key']?>";
         var duration = "<?php if($package['duration']=="other"){
                  $duration =$package['how_many'].$package['d_m_y'];
                  echo  $duration;
         }else{
               echo $package['duration'];
         }
         ?>";
         var amount = <?=$package['cost']?>;
         var owner_id= <?=$owner_id?>;

        stripe_payment(owner_id, price, amount, duration, package_id);
       
       });

    });

    function stripe_payment(owner_id , price , amount , duration, package_id) {
        
        var success_url = '<?php echo base_url() . '/admin/payment_method_check' ?>';
        var cancel_url = '<?php echo base_url(); ?>';

        var _key = '<?php echo STRIPE_KEY; ?>';
         
         
        var stripe = Stripe(_key);

        
        // When the customer clicks on the button, redirect
        // them to Checkout.
        stripe.redirectToCheckout({
          lineItems: [{price: price, quantity: 1}],
          mode: 'subscription',
          successUrl: success_url+'/'+owner_id+'/'+amount+'/'+duration+'/'+package_id+'/{CHECKOUT_SESSION_ID}',
          cancelUrl: cancel_url,
        })
        .then(function (result) {
         
          if (result.error) {
            var displayError = document.getElementById('error-message');
            displayError.textContent = result.error.message;
          }
        });
    }

   </script>

</body>
</html>