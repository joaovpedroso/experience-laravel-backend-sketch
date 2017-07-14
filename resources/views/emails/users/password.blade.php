<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="border-collapse:collapse;height:100%;margin:0;padding:0;width:100%;background-color:#f8f9fa">
  <tbody>
    <tr>
      <td align="center" valign="top" style="height:100%;margin:0;padding:40px;width:100%;font-family:Helvetica,Arial,sans-serif;line-height:160%">

        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;width:600px;background-color:#ffffff;border:1px solid #eff1f3">
          <tbody>
            <tr>
              <td align="center" valign="top" style="font-family:Helvetica,Arial,sans-serif;line-height:160%">

                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
                  <tbody>
                    <tr>
                      <td align="center" style="background-color:#ffffff;font-family:Helvetica,Arial,sans-serif;line-height:160%;padding-top:40px;padding-bottom:40px;background:#fff">
                        <h1>Siscamp</h1>
                      </td>
                    </tr>
                  </tbody>
                </table>

              </td>
            </tr>
            <tr>
              <td style="font-family:Helvetica,Arial,sans-serif;line-height:160%">

                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;background-color:#ffffff;border-top:1px solid #ffffff;border-bottom:1px solid #ffffff">
                  <tbody>
                    <tr>
                      <td valign="top" style="font-family:Helvetica,Arial,sans-serif;line-height:100%;height:80px;color:#ffffff;font-size:26px;font-weight:normal;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;text-align:center;vertical-align:middle;background-color:#00669b;">
                        <p style="max-width:600px;border:0;line-height:100%;outline:none;text-decoration:none;vertical-align:middle;">
                          Alteração de Senha
                        </p>
                      </td>
                    </tr>
                  </tbody>
                </table>


              </td>
            </tr>
            <tr>
              <td style="font-family:Helvetica,Arial,sans-serif;line-height:160%;color:#606060;font-size:16px;padding-top:38px;padding-bottom:38px;padding-right:38px;padding-left:38px;background:#ffffff">

                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;background-color:#ffffff;color:#606060">
                  <tbody>
                    <tr>
                      <td style="font-family:Helvetica,Arial,sans-serif;line-height:160%;padding-bottom:26px;text-align:left">
                        <h2 style="display:block;font-family:Helvetica,Arial,sans-serif;font-style:normal;font-weight:normal;line-height:100%;letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;text-align:left;color:#606060;font-size:16px">
                          Olá, <strong style="color:#00669b;font-weight:600">{{ $user->name }}</strong>!
                        </h2>
                      </td>
                    </tr>

                    <tr>
                      <td style="font-size:14px;font-family:Helvetica,Arial,sans-serif;line-height:160%;padding-bottom:32px;text-align:left">
                        <p style="margin:0">
                          A sua senha do Siscamp foi alterada. <br>
                          Segue abaixo a nova senha: <br>
                        </p>
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size:16px;font-family:Helvetica,Arial,sans-serif;line-height:160%;padding-bottom:42px;text-align:center;padding-top:10px;">
                        <span style="margin:0;padding:15px;background-color:#93c94a;color:#ffffff;border-radius:3px;text-decoration:none;font-weight:600;">
                          {{ $new_password }}
                        </span>
                      </td>
                    </tr>

                  </tbody>
                </table>

              </td>
            </tr>
          </tbody>
        </table>

      </td>
    </tr>
  </tbody>
</table>
