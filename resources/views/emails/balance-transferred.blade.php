@component('mail::message')
<p style="margin: 0;">Dear "{{ $transaction->to_user->name }}",<br>
You have received {{ number_format($transaction->converted_amount,2,'.',',').' '.$transaction->to_currency->code }} from 
{{ $transaction->from_user->name }}. <br><br>
Your current balance is now {{ number_format($transaction->to_user->balance,2,'.',',').' '.$transaction->to_currency->code }}.<br><br>
</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
