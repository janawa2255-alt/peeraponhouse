<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ใบแจ้งหนี้ค่าเช่าห้อง</title>
</head>

<body style="margin:0; padding:0; background:#f5f6fa; font-family:'Prompt', sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f6fa; padding:20px 0;">
        <tr>
            <td align="center">

                {{-- กล่องหลัก --}}
                <table width="600" cellpadding="0" cellspacing="0"
                       style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.08);">

                    {{-- ส่วนหัว --}}
                    <tr>
                        <td style="background:#111827; padding:25px; text-align:center; color:#ffffff;">
                            <h2 style="margin:0; font-size:22px; font-weight:600;">
                                ใบแจ้งหนี้ค่าเช่าห้อง
                            </h2>
                        </td>
                    </tr>

                    {{-- เนื้อหา --}}
                    <tr>
                        <td style="padding:30px 40px; color:#333333; font-size:15px; line-height:1.6;">

                            <p style="margin-bottom:12px;">
                                เรียนคุณ <strong>{{ $invoice->expense->lease->tenants->name ?? '-' }}</strong>
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:15px;">
                                <tr>
                                    <td style="padding:6px 0; color:#555;">เลขที่ใบแจ้งหนี้:</td>
                                    <td style="padding:6px 0; text-align:right; font-weight:600;">
                                        {{ $invoice->invoice_code }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:6px 0; color:#555;">สถานะ:</td>
                                    <td style="padding:6px 0; text-align:right;">
                                        @if($invoice->status == 0)
                                            <span style="color:#d97706; font-weight:600;">รอชำระ</span>
                                        @elseif($invoice->status == 1)
                                            <span style="color:#10b981; font-weight:600;">ชำระแล้ว</span>
                                        @elseif($invoice->status == 2)
                                            <span style="color:#ef4444; font-weight:600;">เกินกำหนด</span>
                                        @else
                                            <span style="color:#6b7280; font-weight:600;">ยกเลิก</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:6px 0; color:#555;">กำหนดชำระ:</td>
                                    <td style="padding:6px 0; text-align:right; font-weight:600;">
                                        {{ optional($invoice->due_date)->format('d/m/Y') }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:6px 0; color:#555;">ยอดรวมสุทธิ:</td>
                                    <td style="padding:6px 0; text-align:right; font-weight:700; font-size:18px;">
                                        {{ number_format($invoice->expense->total_amount, 0) }} บาท
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-top:25px;">
                                กรุณาชำระค่าบริการภายในวันที่กำหนด หากชำระแล้วสามารถละเว้นข้อความนี้ได้ครับ 🙏  
                            </p>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f3f4f6; padding:16px; text-align:center; font-size:13px; color:#6b7280;">
                            ระบบแจ้งเตือนอัตโนมัติ – หอพักพีระพล เฮ้าส์
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
