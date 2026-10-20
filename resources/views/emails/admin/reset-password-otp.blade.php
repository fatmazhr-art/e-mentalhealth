<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Kata Sandi</title>
</head>
<body style="margin: 0; padding: 0; background-color: #FFF8F0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #262626;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #FFF8F0; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 520px; background-color: #FFFFFF; border-radius: 16px; border: 1px solid #E5E5E5; box-shadow: 0 4px 16px rgba(92, 16, 16, 0.08); overflow: hidden;" cellspacing="0" cellpadding="0" border="0">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #5C1010 0%, #8B1E1E 100%); padding: 28px 32px; text-align: center;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #FFFFFF; letter-spacing: 2px;">CURHATIN</h1>
                            <p style="margin: 6px 0 0; font-size: 13px; color: #EEB0B0;">Sistem E-Mental Health Kampus</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            <h2 style="margin: 0 0 12px; font-size: 20px; color: #3B0A0A; font-weight: 700;">Halo, {{ $nama }}</h2>
                            <p style="margin: 0 0 20px; font-size: 14px; line-height: 1.6; color: #525252;">
                                Kami menerima permintaan untuk mengatur ulang kata sandi akun Admin CURHATIN Anda. Gunakan kode One-Time Password (OTP) di bawah ini untuk melanjutkan:
                            </p>

                            <!-- OTP Box -->
                            <div style="background-color: #FFF8F0; border: 2px dashed #D88080; border-radius: 12px; padding: 20px; text-align: center; margin: 24px 0;">
                                <span style="font-size: 12px; font-weight: 700; color: #7A1A1A; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 6px;">Kode OTP Anda</span>
                                <div style="font-size: 34px; font-weight: 800; letter-spacing: 10px; color: #5C1010; font-family: 'Courier New', Courier, monospace;">
                                    {{ $otp }}
                                </div>
                                <span style="font-size: 12px; color: #737373; display: block; margin-top: 6px;">
                                    Berlaku selama <strong>15 menit</strong>
                                </span>
                            </div>

                            <p style="margin: 0 0 16px; font-size: 13px; line-height: 1.6; color: #737373;">
                                Jika Anda tidak merasa melakukan permintaan reset kata sandi ini, abaikan email ini. Akun Anda tetap aman dan kata sandi Anda tidak akan berubah.
                            </p>

                            <hr style="border: none; border-top: 1px solid #F5F5F5; margin: 24px 0;">

                            <p style="margin: 0; font-size: 12px; color: #A3A3A3; line-height: 1.5; text-align: center;">
                                Demi keamanan, jangan pernah membagikan kode OTP ini kepada siapa pun termasuk pihak CURHATIN.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #FAFAFA; border-top: 1px solid #E5E5E5; padding: 18px 32px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #737373;">
                                &copy; {{ date('Y') }} CURHATIN E-Mental Health. Seluruh hak cipta dilindungi.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
