<p>Hello {{ $fullName }},</p>

<p>Your CollegeCare counsellor account has been created by the admin team.</p>

<p><strong>Account details</strong></p>
<ul>
    <li><strong>Full name:</strong> {{ $fullName }}</li>
    <li><strong>Phone:</strong> {{ $phone ?: 'Not provided' }}</li>
    <li><strong>Email:</strong> {{ $email }}</li>
    <li><strong>Password:</strong> {{ $password }}</li>
</ul>

<p>You can sign in here: <a href="{{ $signinUrl }}">{{ $signinUrl }}</a></p>

<p>For security, please change your password after your first login.</p>

<p>Best regards,<br>CollegeCare Team</p>
