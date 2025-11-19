<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การชำระเงินได้รับการอนุมัติ</title>
    <style>
        body {
            font-family: 'Sarabun', Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .success-icon {
            text-align: center;
            margin-bottom: 20px;
        }
        .success-icon svg {
            width: 60px;
            height: 60px;
            fill: #22c55e;
        }
        .info-box {
            background-color: #f9fafb;
            border-left: 4px solid #ea580c;
            padding: 15px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            color: #6b7280;
            font-weight: 500;
        }
        .value {
            color: #111827;
            font-weight: 600;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background-color: #ea580c;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏠 พีระพลเฮ้าส์</h1>
        </div>

        <div class="content">
            <div class="success-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                </svg>
            </div>

            <h2 style="color: #22c55e; text-align: center; margin-bottom: 10px;">การชำระเงินสำเร็จ!</h2>
            <p style="text-align: center; color: #6b7280; margin-bottom: 30px;">
                การชำระเงินของคุณได้รับการอนุมัติเรียบร้อยแล้ว
            </p>

            <div class="info-box">
                <h3 style="margin-top: 0; color: #111827;">รายละเอียดการชำระเงิน</h3>
                
                <div class="info-row">
                    <span class="label">เลขที่ใบแจ้งหนี้:</span>
                    <span class="value">{{ $payment->invoice->invoice_code ?? '-' }}</span>
                </div>

                <div class="info-row">
                    <span class="label">ห้องเช่า:</span>
                    <span class="value">{{ $payment->invoice->expense->lease->rooms->room_no ?? '-' }}</span>
                </div>

                <div class="info-row">
                    <span class="label">ยอดเงินที่ชำระ:</span>
                    <span class="value">{{ number_format($payment->total_amount, 2) }} บาท</span>
                </div>

                <div class="info-row">
                    <span class="label">วันที่ชำระ:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($payment->paid_date)->locale('th')->translatedFormat('d F Y') }}</span>
                </div>

                <div class="info-row">
                    <span class="label">ธนาคาร:</span>
                    <span class="value">{{ $payment->bank->bank_name ?? '-' }}</span>
                </div>

                <div class="info-row">
                    <span class="label">สถานะ:</span>
                    <span class="value" style="color: #22c55e;">✓ อนุมัติแล้ว</span>
                </div>
            </div>

            <p style="color: #6b7280; font-size: 14px; margin-top: 20px;">
                ขอบคุณที่ชำระเงินตรงเวลา หากมีข้อสงสัยหรือต้องการความช่วยเหลือ กรุณาติดต่อเจ้าหน้าที่
            </p>
        </div>

        <div class="footer">
            <p style="margin: 5px 0;">พีระพลเฮ้าส์</p>
            <p style="margin: 5px 0;">99/9 หมู่ 3 ถนนเอเชีย ตำบลเมือง จังหวัดพระนครศรีอยุธยา 67000</p>
            <p style="margin: 5px 0;">อีเมลนี้ส่งโดยระบบอัตโนมัติ กรุณาอย่าตอบกลับ</p>
        </div>
    </div>
</body>
</html>
