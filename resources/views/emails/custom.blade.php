<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectLine ?? 'Notification' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* General Reset */
        body, html { margin: 0; padding: 0; width: 100%; background: #f6f9fa; font-family: 'Nunito Sans', Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; }
        img { max-width: 100%; display: block; }

        /* Container */
        .container {
            max-width: 650px;
            margin: 24px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(60,72,88,0.08);
        }

        /* Header */
        .header {
            background: #183153;
            color: #fff;
            padding: 40px 20px 20px;
            text-align: center;
        }
        .header img { width: 60px; margin-bottom: 12px; }
        .header h1 { margin: 0; font-size: 1.8rem; font-weight: 800; }

        /* Content */
        .content {
            padding: 32px 24px 24px;
            font-size: 1.06rem;
            color: #2e2e2e;
            line-height: 1.7;
        }
        .content h2 {
            margin-top: 0;
            color: #308e87;
            font-size: 1.4rem;
        }
        .btn {
            background: #308e87;
            color: #fff !important;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 700;
            display: inline-block;
            margin: 20px 0 24px;
            box-shadow: 0 2px 10px rgba(48,142,135,0.15);
            font-size: 1.05rem;
        }
        .btn:hover { background: #22635e; }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 30px 0 20px;
        }

        /* Footer */
        .footer-wrapper { background: #f6f9fa; }
        .footer { padding: 24px 18px; font-size: 13px; color: #666; text-align: left; }
        .footer h4 { margin: 0 0 10px; font-size: 14px; color: #183153; }
        .footer a { color: #308e87; text-decoration: underline; }
        .footer .unsubscribe { color: #bb3535; text-decoration: underline; font-size: 12px; }

        /* Social */
        .social { margin: 12px 0; }
        .social a { display: inline-block; margin: 0 4px; }
        .social img { width: 28px; height: 28px; border-radius: 50%; }

        /* Advert */
        .advert {
            text-align: center;
            padding: 15px;
        }
        .advert img {
            max-width: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        /* Responsive */
        @media only screen and (max-width:650px) {
            .container { width: 95% !important; }
            .content, .header, .footer { padding: 16px !important; }
            .btn { padding: 10px 22px; font-size: 1rem; }
            .footer-layout { display: block !important; }
            .advert { margin-top: 20px; }
        }
    </style>
</head>
<body>
<table class="container" cellpadding="0" cellspacing="0" role="presentation">


  <!-- Content -->
  <tr>
    <td class="content">
      <h2>{{ $title }}</h2>
      <p>{!! nl2br(e($content)) !!}</p>

      @if($buttonUrl && $buttonText)
        <div style="text-align:center;">
          <a href="{{ $buttonUrl }}" class="btn">{{ $buttonText }}</a>
        </div>
      @endif

      <p style="margin: 28px 0 0;">Best Regards,<br>
         <span style="font-weight: bold; color:#183153;">{{ config('app.name') }}</span>
      </p>

      <hr class="divider">
    </td>
  </tr>

  <!-- Footer + Advert Section -->
  <tr>
    <td class="footer-wrapper">
      <table width="100%" cellpadding="0" cellspacing="0" role="presentation" class="footer-layout" style="display:flex; flex-wrap:wrap;">
        <tr>
          <!-- Footer Info -->
          <td class="footer" style="width:65%; vertical-align:top;">
            <h4>Stay Connected</h4>
            <div class="social">
              <!-- Facebook -->
<a href="#" target="_blank" style="margin:0 6px; text-decoration:none;">
  <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#1877f2" viewBox="0 0 24 24">
    <path d="M22 12.07C22 6.48 17.52 2 12 2S2 6.48 2 12.07c0 4.99 3.66 9.12 8.44 9.93v-7.03H7.9v-2.9h2.54V9.41c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.9h-2.34v7.03C18.34 21.19 22 17.06 22 12.07z"/>
  </svg>
</a>

<!-- Twitter -->
<a href="#" target="_blank" style="margin:0 6px; text-decoration:none;">
  <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#1da1f2" viewBox="0 0 24 24">
    <path d="M22.46 6c-.77.34-1.6.56-2.46.66a4.2 4.2 0 0 0 1.85-2.32c-.8.48-1.7.82-2.65 1a4.18 4.18 0 0 0-7.12 3.82A11.88 11.88 0 0 1 3.16 4.9a4.18 4.18 0 0 0 1.3 5.57c-.7-.02-1.36-.21-1.94-.53v.05c0 2.04 1.45 3.74 3.37 4.12a4.2 4.2 0 0 1-1.89.07 4.19 4.19 0 0 0 3.91 2.9A8.38 8.38 0 0 1 2 19.54a11.82 11.82 0 0 0 6.41 1.88c7.69 0 11.9-6.36 11.9-11.9v-.54c.81-.59 1.51-1.33 2.07-2.18z"/>
  </svg>
</a>

<!-- Instagram -->
<a href="#" target="_blank" style="margin:0 6px; text-decoration:none;">
  <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#e4405f" viewBox="0 0 24 24">
    <path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9A5.5 5.5 0 0 1 16.5 22h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9zm4.5 3a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11zm0 2a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7zm5.25-.88a1.13 1.13 0 1 1 0 2.25 1.13 1.13 0 0 1 0-2.25z"/>
  </svg>
</a>

<!-- LinkedIn -->
<a href="#" target="_blank" style="margin:0 6px; text-decoration:none;">
  <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#0077b5" viewBox="0 0 24 24">
    <path d="M4.98 3a2 2 0 1 0 0 4.001A2 2 0 0 0 4.98 3zM3 8.5h4v12H3v-12zm7.5 0h3.6v1.64h.05c.5-.94 1.72-1.93 3.55-1.93 3.8 0 4.5 2.5 4.5 5.7v6.59h-4v-5.83c0-1.39-.02-3.18-1.94-3.18-1.94 0-2.24 1.51-2.24 3.07v5.94h-4v-12z"/>
  </svg>
</a>
</div>
            <div>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
            <div style="margin:6px 0;">Need help? <a href="mailto:fee24mfb@gmail.com">Contact Support</a></div>
            <div><a href="#" class="unsubscribe">Unsubscribe</a></div>
          </td>

        <td class="advert" style="width:35%; vertical-align:middle;">
        <img src="{{ $message->embed(storage_path('app/public/email/001.png')) }}" alt="Special Offer">


          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
