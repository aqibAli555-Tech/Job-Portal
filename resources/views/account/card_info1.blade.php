<!DOCTYPE html>
<html lang="en">
<?php
$packeg_name = json_decode($data['package']['name']);
?>
<head>
    <title>Paying ...</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://goSellJSLib.b-cdn.net/v1.6.0/css/gosell.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://goSellJSLib.b-cdn.net/v1.6.0/js/gosell.js" type="text/javascript"></script>
    <style>
        .gosell-gateway-msg-wrapper {
            padding-top: 30px;
        }

        frame#gosell-gateway .gosell-gateway-msg-title {
            color: #000 !important;
        }
    </style>
</head>
<body>

<script>
    function save() {
        goSell.config({
            gateway: {
                publicKey: "pk_test_tw1Bo0PsFdQIYNcSCuZVmn8A",
                merchant_id: "1124340",
                language: "en",
                contactInfo: false,
                supportedCurrencies: "all",
                supportedPaymentMethods: "all",
                saveCardOption: true,
                customerCards: true,
                notifications: "standard",
                callback: (response) => {
                    console.log("callback", response);
                },
                onClose: () => {
                    window.location.replace("<?= $data['error'] ?>");
                },
                onLoad: () => {
                    goSell.openLightBox();
                    // goSell.openPaymentPage();
                },
                style: {
                    base: {
                        color: "red",
                        lineHeight: "10px",
                        fontFamily: "sans-serif",
                        fontSmoothing: "antialiased",
                        fontSize: "10px",
                        "::placeholder": {
                            color: "rgba(0, 0, 0, 0.26)",
                            fontSize: "10px",
                        },
                    },
                    invalid: {
                        color: "red",
                        iconColor: "#fa755a ",
                    },
                },
            },
            customer: {
                first_name: "<?= $data['user']['name'] ?>",
                middle_name: "",
                last_name: "",
                email: "<?= $data['user']['email'] ?>",
                phone: {
                    country_code: {
        {
            config('country.phone')
        }
    },
        "<?=$data['user']['phone']?>",
    },
    },
        {
            "<?=$data['package']['price']?>",
                currency
        :
            "<?=$data['package']['currency_code']?>",
                items
        :
            [
                {
                    id: "<?= $data['package']['id']?>",
                    name: "<?= $packeg_name->en ?>",
                    description: "",
                    old_quantity: 1,
                    quantity: 1,
                    amount_per_unit: "<?= $data['package']['price']?>",
                    old_total_amount: "<?= $data['package']['price']?>",
                    total_amount: "<?=$data['package']['price']?>",
                },
            ],
        }
    ,

        {
            "charge",
                charge
        :
            {
                {
                    100,
                        type
                :
                    "VOID",
                }
            ,
                false,
                    threeDSecure
            :
                true,
                    description
            :
                "description",
                    statement_descriptor
            :
                "statement_descriptor",
                    reference
            :
                {
                    "",
                        order
                :
                    "<?=$data['package']['id']?>",
                }
            ,
                {
                }
            ,
                {
                    false,
                        sms
                :
                    true,
                }
            ,
                "<?= $data['redirect'] ?>",
                    post
            :
                "<?= $data['success'] ?>",
            }
        ,
        }
    ,
    })

    }

    $(document).ready(function () {
        save();
    });
</script>
</body>
</html>

<?php
die;
?>