<x-mail::message>
# Password Reset Request

Hi {{ $studentName }},

We received a request to reset your **CollegeCare** password.

<x-mail::panel>
For your security, this link expires in **{{ $expireMinutes }} minutes**.
</x-mail::panel>

<x-mail::button :url="$resetUrl" color="primary">
Reset Password
</x-mail::button>

If you didn’t request this, you can safely ignore this email.

Need help? Contact support and we’ll assist you quickly.

Regards,
**CollegeCare Support**
</x-mail::message>
