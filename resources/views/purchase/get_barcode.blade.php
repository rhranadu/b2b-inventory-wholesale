<style>
    @font-face{
        font-family:'IDAutomationHC39M';
        src:url('/backend/fonts/IDAutomationHC39M-Code-39-Barcode.ttf');
    }
    .barcodeSingle {
        display: inline-block;
        border: solid 1px black;
        padding: 5px;
        margin-bottom: 5px;
        min-width: 170px;
        text-align: center;
        min-height: 70px;
    }
    .barcodeSingle * {
        font-family: monospace;
        font-size:11px;
    }
    .barcodeSingle .barcode {
        font-family:'IDAutomationHC39M',serif;
        line-height: 4;
    }
    @media print {
        .d-print-none {
            display: none !important;
        }
    }
</style>
<button type="button" class="btn btn-sm btn-primary d-print-none print d-block"><i class="fa fa-print"></i> Print</button>
@foreach($purchase_detail->barCodes as $barCode)
    <div class="barcodeSingle">
        @if(isset(auth()->user()->vendor->name))
            <div>{!! auth()->user()->vendor->name !!}</div>
        @endif
        <div>{!! $purchase_detail->product->name !!}</div>
        <div class="barcode">{!! $barCode->bar_code !!}</div>
        <div>POS Price: BDT {!! ($purchase_detail->product->pos_discount_price) ? number_format($purchase_detail->product->pos_discount_price, 2) : number_format($purchase_detail->product->max_price, 2) !!}</div>
        <div>Marketplace Price: BDT {!! ($purchase_detail->product->marketplace_discount_price) ? number_format($purchase_detail->product->marketplace_discount_price, 2) : number_format($purchase_detail->product->max_price, 2) !!}</div>
    </div>
@endforeach
