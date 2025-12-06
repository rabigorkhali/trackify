<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #ff9f43;
            color: white;
            padding: 20px;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f7fafc;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }
        .ticket-info {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #ff9f43;
        }
        .status-change {
            background: #fff3cd;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #ff9f43;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #ff9f43;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #718096;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Ticket Status Changed</h2>
        </div>
        
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            
            <p><strong>{{ $changedBy->name }}</strong> has changed the status of ticket <strong>{{ $ticket->ticket_key }}</strong>.</p>
            
            <div class="status-change">
                <p style="margin: 0; font-size: 16px;">
                    <strong>Status changed from:</strong> <span style="color: #718096;">{{ $oldStatus }}</span><br>
                    <strong>Status changed to:</strong> <span style="color: #28c76f; font-weight: bold;">{{ $newStatus }}</span>
                </p>
            </div>
            
            <div class="ticket-info">
                <h3 style="margin-top: 0;">{{ $ticket->title }}</h3>
                <p><strong>Ticket:</strong> {{ $ticket->ticket_key }}</p>
                <p><strong>Project:</strong> {{ $ticket->project->name ?? 'N/A' }}</p>
                <p><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
            </div>
            
            <a href="{{ rtrim(config('app.url'), '/') }}/{{ getSystemPrefix() }}/projects/{{ $ticket->project_id }}/tickets/{{ $ticket->id }}/show" class="button">
                View Ticket
            </a>
            
            <p style="margin-top: 20px; color: #718096; font-size: 13px;">
                You're receiving this email because you are assigned to this ticket or are watching it.
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

