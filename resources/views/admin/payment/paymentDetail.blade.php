<div class="modal fade d-print" id="detailPayment{{$payment->id}}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				@php
					$words = explode(' ', $payment->customer->name); // Split the name into individual words
					if (count($words) === 1) {
						// If the name has only one word, retrieve the first two characters
						$initialName = Str::limit($payment->customer->name, 2, '');
					} else {
						// Retrieve the first character of each word
						$initialName = '';
						foreach ($words as $word) {
							$initialName .= Str::substr($word, 0, 1);
						}
					}
				@endphp
				@if($payment->customer->attachments)
					<img src="{{ asset('storage/uploads/'.$payment->customer->id.'/'.$payment->customer->attachments) }}" class="card-img-top cover" alt="cover" style="max-width: 100%;">
				@else
					<div class="card-img-top cover align-items-center justify-content-center text-center" style="background-color: #e9e9e9">
						<h1 class="display-1 fw-500">{{$initialName}}</h1>
					</div>
				@endif

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<div class="modal-body">
				<h5 class="fw-500">Payment/Deposit Detail</h5>
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between">
						<span>Slip Date</span>
						<span>{{ date('d M Y, H:i:s', strtotime($payment->slip_date)) }}</span>
					</li>
					<li class="list-group-item d-flex justify-content-between">
						<span>Transfer Amount</span>
						<span>Rp {{ number_format($payment->amount, 2, '.', ',') }}</span>
					</li>
					<li class="list-group-item d-flex justify-content-between">
						<span>Transfer to</span>
						<span>
							@if($payment->bank_id)
							{{$payment->bank->bank}}, {{$payment->bank->account}}
							@endif
						</span>
					</li>
					<li class="list-group-item d-flex justify-content-between">
						<span>Balance</span>
						<span class="{{ $payment->deposit === null ? 'text-danger' : ($payment->deposit <= 0 ? 'text-danger' : 'text-primary') }}">
							{{ $payment->deposit ?? '0' }}
						</span>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
