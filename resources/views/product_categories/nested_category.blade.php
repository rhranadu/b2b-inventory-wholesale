<ul>
    @foreach($childes as $child)
        @if(isset($productCategory))
            @if($productCategory->id != $child->id)
                <li>
                    <a href="#" class="select-category" data-id="{{ $child->id }}"> {{ $child->name }}</a>
                    @if(count($child->childes))
                        @include('product_categories.nested_category',['childes' => $child->childes, 'productCategory' => $productCategory])
                    @endif
                </li>
            @endif
        @else
            <li>
                <a href="#" class="select-category" data-id="{{ $child->id }}"> {{ $child->name }}</a>
                @if(count($child->childes))
                    @include('product_categories.nested_category',['childes' => $child->childes])
                @endif
            </li>            
        @endif
    @endforeach
</ul>
