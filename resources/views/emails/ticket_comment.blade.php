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
            background: #5a67d8;
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
            border-left: 4px solid #5a67d8;
        }
        .comment-box {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #5a67d8;
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
            <h2>New Comment on Ticket</h2>
        </div>
        
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            
            <p><strong>{{ $commenter->name }}</strong> added a new comment on ticket <strong>{{ $ticket->ticket_key }}</strong>.</p>
            
            <div class="ticket-info">
                <h3 style="margin-top: 0;">{{ $ticket->title }}</h3>
                <p><strong>Ticket:</strong> {{ $ticket->ticket_key }}</p>
                <p><strong>Project:</strong> {{ $ticket->project->name ?? 'N/A' }}</p>
                <p><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
                <p><strong>Status:</strong> {{ $ticket->ticketStatus->name ?? 'N/A' }}</p>
            </div>
            
            <div class="comment-box">
                <p><strong>Comment:</strong></p>
                <p style="white-space: pre-wrap;">{{ $comment }}</p>
                <p style="color: #718096; font-size: 12px; margin-top: 10px;">
                    - {{ $commenter->name }}, {{ $commentDate }}
                </p>
            </div>
            
            <a href="{{ config('app.url') }}/{{ getSystemPrefix() }}/projects/{{ $ticket->project_id }}/tickets/{{ $ticket->id }}/show" class="button">
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

