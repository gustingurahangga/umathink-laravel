<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmaThink - Belajar &amp; Bermain</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
</head>
<body style="margin: 0; padding: 0; background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <!-- Wrapper Table -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f0f2f5; padding: 30px 10px;">
        <tr>
            <td align="center">

                <!-- Email Container -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 520px; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">

                    <!-- Header dengan Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #2b4c7e 0%, #1a365d 100%); padding: 40px 30px; text-align: center;">
                           

                            <p style="color: rgba(255,255,255,0.85); font-size: 14px; margin: 0; letter-spacing: 0.5px;">
                                Platform Latihan Ujian Kedinasan
                            </p>
                        </td>
                    </tr>

                    <!-- Maskot dan Greeting -->
                    <tr>
                        <td style="padding: 35px 30px 10px 30px; text-align: center;">

                            <h1 style="margin: 0 0 8px 0; font-size: 22px; color: #1a1a2e; font-weight: 800;">
                                Verifikasi Akun Anda
                            </h1>
                            <p style="margin: 0; font-size: 14px; color: #666; line-height: 1.6;">
                                Halo! Terima kasih telah mendaftar di <strong style="color: #2b4c7e;">UmaThink</strong>.<br>
                                Masukkan kode OTP berikut untuk mengaktifkan akun Anda:
                            </p>
                        </td>
                    </tr>

                    <!-- Kode OTP -->
                    <tr>
                        <td style="padding: 25px 30px; text-align: center;">
                            <div style="background: linear-gradient(135deg, #FFFDF5 0%, #FFF8E1 100%); border: 2px dashed #F8CB2E; border-radius: 16px; padding: 25px 20px; display: inline-block; min-width: 280px;">
                                <p style="margin: 0 0 8px 0; font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 2px; font-weight: 700;">
                                    Kode Verifikasi
                                </p>
                                <p style="margin: 0; font-size: 42px; font-weight: 900; letter-spacing: 12px; color: #2b4c7e; font-family: 'Courier New', monospace;">
                                    {{ $otp }}
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Peringatan -->
                    <tr>
                        <td style="padding: 0 30px 25px 30px; text-align: center;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #FFF5F5; border-radius: 12px; border-left: 4px solid #E74C3C;">
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <p style="margin: 0; font-size: 13px; color: #C0392B; font-weight: 700;">
                                            ⚠️ Penting:
                                        </p>
                                        <p style="margin: 5px 0 0 0; font-size: 12px; color: #666; line-height: 1.5;">
                                            Jangan bagikan kode ini kepada siapapun.<br>
                                            Kode ini hanya berlaku untuk satu kali penggunaan.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 30px;">
                            <hr style="border: none; border-top: 1px solid #EEE; margin: 0;">
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 25px 30px 30px 30px; text-align: center;">
                            <p style="margin: 0 0 5px 0; font-size: 13px; color: #999; line-height: 1.5;">
                                Jika Anda tidak merasa melakukan pendaftaran,<br>
                                abaikan email ini.
                            </p>
                            <p style="margin: 15px 0 0 0; font-size: 12px; color: #bbb;">
                                &copy; {{ date('Y') }} <strong>UmaThink</strong> &mdash;
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>