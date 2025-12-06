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
            background: #28c76f;
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
            border-left: 4px solid #28c76f;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #28c76f;
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
        .priority-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        .priority-low { background: #00cfe8; color: white; }
        .priority-medium { background: #ff9f43; color: white; }
        .priority-high { background: #ff6b6b; color: white; }
        .priority-critical { background: #ea5455; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Ticket Assigned to You</h2>
        </div>
        
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            
            <p><strong>{{ $assignedBy->name }}</strong> has assigned a new ticket to you.</p>
            
            <div class="ticket-info">
                <h3 style="margin-top: 0;">{{ $ticket->title }}</h3>
                <p><strong>Ticket:</strong> {{ $ticket->ticket_key }}</p>
                <p><strong>Project:</strong> {{ $ticket->project->name ?? 'N/A' }}</p>
                <p><strong>Priority:</strong> 
                    <span class="priority-badge priority-{{ $ticket->priority }}">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </p>
                <p><strong>Type:</strong> {{ ucfirst($ticket->type) }}</p>
                <p><strong>Status:</strong> {{ $ticket->ticketStatus->name ?? 'N/A' }}</p>
                @if($ticket->due_date)
                <p><strong>Due Date:</strong> {{ $ticket->due_date->format('M d, Y') }}</p>
                @endif
                @if($ticket->description)
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                    <strong>Description:</strong>
                    <div style="margin-top: 10px; color: #4a5568;">
                        {!! $ticket->description !!}
                    </div>
                </div>
                @endif
            </div>
            
            <a href="{{ rtrim(config('app.url'), '/') }}/{{ getSystemPrefix() }}/projects/{{ $ticket->project_id }}/tickets/{{ $ticket->id }}/show" class="button">
                View Ticket
            </a>
            
            <p style="margin-top: 20px; color: #718096; font-size: 13px;">
                You're receiving this email because a ticket has been assigned to you.
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

