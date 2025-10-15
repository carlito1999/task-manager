<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Manager Notification')</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #3B82F6, #1E40AF);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-content {
            padding: 30px;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3B82F6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 16px 0;
        }
        .btn:hover {
            background-color: #1E40AF;
        }
        .task-info, .project-info {
            background-color: #f8f9fa;
            border-left: 4px solid #3B82F6;
            padding: 16px;
            margin: 16px 0;
            border-radius: 0 6px 6px 0;
        }
        .priority-high {
            border-left-color: #EF4444;
        }
        .priority-medium {
            border-left-color: #F59E0B;
        }
        .priority-low {
            border-left-color: #10B981;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }
        .status-todo {
            background-color: #F3F4F6;
            color: #374151;
        }
        .status-in_progress {
            background-color: #DBEAFE;
            color: #1E40AF;
        }
        .status-done {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .comment-box {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 16px;
            margin: 16px 0;
            border-left: 4px solid #3B82F6;
        }
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .meta-info {
            color: #6c757d;
            font-size: 14px;
            margin: 8px 0;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .email-header, .email-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>@yield('header', 'Task Manager')</h1>
        </div>
        
        <div class="email-content">
            @yield('content')
        </div>
        
        <div class="email-footer">
            <p>This email was sent from your Task Manager application.</p>
            <p>© {{ date('Y') }} Task Manager. All rights reserved.</p>
        </div>
    </div>
</body>
</html>