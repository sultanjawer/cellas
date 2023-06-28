<div class="modal fade d-print" id="detailOrder{{$order->id}}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				@php
					$words = explode(' ', $order->customer->name); // Split the name into individual words
					if (count($words) === 1) {
						// If the name has only one word, retrieve the first two characters
						$initialName = Str::limit($order->customer->name, 2, '');
					} else {
						// Retrieve the first character of each word
						$initialName = '';
						foreach ($words as $word) {
							$initialName .= Str::substr($word, 0, 1);
						}
					}
				@endphp
				@if($order->customer->attachments)
					<img src="{{ asset('storage/uploads/'.$order->customer->id.'/'.$order->customer->attachments) }}" class="card-img-top cover" alt="cover" style="max-width: 100%;">
				@else
					<div class="card-img-top cover align-items-center justify-content-center text-center
					{{ ($sumAmountDeposits->get($order->customer_id) - $sumAmountSellAndCharges->get($order->customer_id)) < 0 ? 'bg-danger-500' : 'bg-success-500' }}">
						<h2 class="display-3 fw-500 p-1">{{$order->customer->name}}</h2>
					</div>
				@endif

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<div class="modal-body">
				<h5 class="fw-500">Order Detail</h5>
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between">
						<span>Order Date</span>
						<span>{{ date('d M Y, H:i:s', strtotime($order->created_at)) }}</span>
					</li>
					<li class="list-group-item d-flex justify-content-between">
						<span>Order Amount</span>
						<span>{{$order->product->symbol}} {{ number_format($order->amount, 2, '.', ',') }}</span>
					</li>
					<li class="list-group-item d-flex justify-content-between">
						<span>Purchased Rate</span>
						<span>Rp {{ number_format($order->sell, 2, '.', ',') }}</span>
					</li>
					<li class="list-group-item d-flex justify-content-between">
						<span>Charges</span>
						<span>Rp {{ number_format($order->charges, 2, '.', ',') }}</span>
					</li>
					<li class="list-group-item d-flex justify-content-between" style="background-color: #e9e9e9">
						<span class="text-muted fw-500">Current order</span>
						<span class="fw-700">Rp {{ number_format($order->amount * $order->sell + $order->charges, 2, '.', ',') }}</span>
					</li>
					<li class="list-group-item d-flex justify-content-between" style="background-color: #e9e9e9">
						<span class="text-muted fw-500">Deposit</span>
						<span class="fw-700">Rp {{ number_format($sumAmountDeposits->get($order->customer_id) - $sumAmountSellAndCharges->get($order->customer_id)+($order->amount * $order->sell + $order->charges), 2, ',', '.') }}</span>
					</li>
					<li class="list-group-item">
						<div class="d-flex justify-content-between">
							<span>Balance</span>
							<span class="fw-500 {{ ($sumAmountDeposits->get($order->customer_id) - $sumAmountSellAndCharges->get($order->customer_id)) < 0 ? 'text-danger' : 'text-primary' }}">
								Rp {{ number_format($sumAmountDeposits->get($order->customer_id) - $sumAmountSellAndCharges->get($order->customer_id), 2, ',', '.') }}
							</span>
						</div>
						<span class="text-muted small">
							@if(($sumAmountDeposits->get($order->customer_id) - $sumAmountSellAndCharges->get($order->customer_id)) < 0)
								(Your balance is insufficient for this purchase. Please make payment immediately.)
							@elseif(($sumAmountDeposits->get($order->customer_id) - $sumAmountSellAndCharges->get($order->customer_id)) === 0)
								(Your balance is insufficient for the next order.)
							@else
								(Your balance after this purchase.)
							@endif
						</span>
					</li>
					<li class="list-group-item">
						Please Transfer to:<br>
						<div class="row d-flex align-items-top justify-content-between">
							<div class="col-6">
								Bank: <span class="fw-500ext-primary">{{$order->bank->bank}}</span><br>
								Account No: <span class="fw-500 text-primary">{{$order->bank->account}}</span><br>
								Account Name: <span class="fw-500 text-primary">{{$order->bank->acc_name}}</span>
							</div>
							<div class="col-6">
								Bank: <span class="fw-500ext-primary">{{$order->bank->bank_1}}</span><br>
								Account No: <span class="fw-500 text-primary">{{$order->bank->account_1}}</span><br>
								Account Name: <span class="fw-500 text-primary">{{$order->bank->acc_name_1}}</span>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
