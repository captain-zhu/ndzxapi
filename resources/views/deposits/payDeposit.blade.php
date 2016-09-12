<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>宁都中学订餐系统-支付</title>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">

        function callpay()
        {
            wx.chooseWXPay({
                timestamp: '{{ $config->timestamp }}',
                nonceStr: '{{ $config->nonceStr }}',
                package: '{{ $config->package }}',
                signType: '{{ $config->signType }}',
                paySign: '{{ $config->paySign}}', // 支付签名
                success: function (res) {
                    // 支付成功后的回调函数
                    location.href = 'http://fz.garmintech.net/deposit/success';
                }
            });
        }
    </script>
</head>
<body>
<br/>
<font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px">{{ $totalFee }}元</span>钱</b></font><br/><br/>
<div align="center">
    <button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >立即支付</button>
</div>
</body>
</html>