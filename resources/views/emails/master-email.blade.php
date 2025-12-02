<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <meta charset="UTF-8">
    <title>{{getConfigTableData()?->company_name}}</title>
    <style>
        /* Reset and base styles */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Link styles */
        a {
            text-decoration: none;
            color: #6b7280;
            transition: color 0.2s ease;
        }
        a:hover {
            color: #4b5563;
        }

        /* Container styles */
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Header styles */
        .header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid #f0f0f0;
        }
        .header h4 {
            color: #1e40af;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        /* Content styles */
        .content {
            padding: 30px 20px;
            font-size: 16px;
            line-height: 1.6;
        }

        /* Footer styles - reduced height */
        .footer {
            padding: 10px 20px;  /* Reduced from 30px to 10px */
            background-color: #f8fafc;
            border-top: 1px solid #f0f0f0;
            text-align: center;
        }

        .logo-container {
            margin-bottom: 7px;  /* Reduced from 20px to 7px */
        }
        .logo-container img {
            height: 35px;  /* Reduced from 50px to 35px */
            width: auto;
        }

        .description {
            margin: 5px 0;  /* Reduced from 15px to 5px */
            font-size: 14px;
            color: #4b5563;
        }

        .contact-info {
            margin: 7px 0;  /* Reduced from 20px to 7px */
            line-height: 1.8;
        }
        .contact-info strong {
            color: #4b5563;
            font-weight: 600;
        }

        .social-icons {
            margin: 8px 0;  /* Reduced from 25px to 8px */
        }
        .social-icons a {
            margin: 0 12px;
            font-size: 20px;
        }
        .social-icons .fa-facebook-square { color: #3b5998; }
        .social-icons .fa-instagram { color: #E1306C; }
        .social-icons .fa-tiktok { color: #000000; }

        .copyright {
            margin-top: 8px;  /* Reduced from 25px to 8px */
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h4 style="color: #2e790b;">{{getConfigTableData()?->company_name}}</h4>
        </div>

        <div class="content">
            @yield('content')
        </div>

        <div class="footer">
            <div class="logo-container">
                <a href="{{ url('/') }}" style="color: #0B3D91;">
                    <img src="{{ asset(getConfigTableData()?->secondary_logo ?? getConfigTableData()?->logo) }}"
                         alt="{{getConfigTableData()?->company_name}} Logo">
                </a>
            </div>

            @if(getConfigTableData()?->description)
                <div class="description">
                    {{ getConfigTableData()?->description }}
                </div>
            @endif

            <div class="contact-info" style="display: flex; justify-content: space-between; font-size: 11px; line-height: 1.3; max-width: 80%; margin: 0 auto;">
                <div class="left-section" style="flex: 1; padding-right: 15px;">
                    <strong>Email:</strong>
                    <a href="mailto:{{ getConfigTableData()?->email }}" style="text-decoration: none;">
                        {{ getConfigTableData()?->email }}
                    </a>
                    <br>
                    <strong>Phone:</strong>
                    <a href="tel:{{ getConfigTableData()?->primary_phone_number }}" style="text-decoration: none;">
                        {{ getConfigTableData()?->primary_phone_number }}
                    </a>
                </div>
                <div class="right-section" style="flex: 1; padding-left: 15px; border-left: 1px solid #ddd;">
                    <strong>Address:</strong>
                    {{ getConfigTableData()?->address_line_1 }} {{ getConfigTableData()?->address_line_2 }}
                    <br>
                    <strong>Website:</strong>
                    <a href="{{ url('/') }}" style="text-decoration: none;">
                        {{ url('/') }}
                    </a>
                    <br>
                    <strong>WhatsApp Group:</strong>
                    <a href="https://chat.whatsapp.com/D9Sk1uYbXxMKfBWq0PQRJ0" style="text-decoration: none; display: inline-block; background-color: #25D366; color: white; padding: 5px 10px; border-radius: 4px; margin-top: 5px;">
                        <i class="fab fa-whatsapp"></i> Join WhatsApp Group
                    </a>
                </div>
            </div>

            <div class="social-icons">
                @if(getConfigTableData()?->facebook_url)
                    <a href="{{ getConfigTableData()?->facebook_url }}">
                        <i class="fab fa-facebook-square"></i>
                    </a>
                @endif
                @if(getConfigTableData()?->instagram_url)
                    <a href="{{ getConfigTableData()?->instagram_url }}">
                        <i class="fab fa-instagram"></i>
                    </a>
                @endif
                @if(getConfigTableData()?->tiktok_url)
                    <a href="{{ getConfigTableData()?->tiktok_url }}">
                        <i class="fab fa-tiktok"></i>
                    </a>
                @endif
            </div>

            <div class="copyright">
                {{ getConfigTableData()?->all_rights_reserved_text ?? 'All rights reserved.' }}
            </div>
        </div>
    </div>
</body>
</html>
