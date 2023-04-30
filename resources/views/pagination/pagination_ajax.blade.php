<?php $data_target_element = $__data[0]; ?>
@if ($paginator->hasPages())
    <div class="row">
    <div class="col-12">
        <div class="main-p-pagination" style="margin: 10px;">
            <nav style="display: flex;">
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')"><a class="page-link btn btn-icon btn-sm border-0 btn-light mr-2 my-1" href="#" aria-label="Previous"><i class="flaticon2-back"></i></a></li>
                    @else
                        <li class="page-item" aria-label="@lang('pagination.previous')"><a class="page-link btn btn-icon btn-sm border-0 btn-light mr-2 my-1" href="{!! $data_target_element !!}" onclick="AJAX_HELPER.ajaxGetPaginateData('{!! $paginator->previousPageUrl() !!}', '{!! $data_target_element !!}', '{!! $paginator->currentPage() - 1 !!}')" aria-label="Previous"><i class="flaticon2-back"></i></a></li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true"><a class="page-link btn btn-icon btn-sm border-0 btn-light mr-2 my-1 active" href="#">{{ $element }}</a></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page"><a class="page-link btn btn-icon btn-sm border-0 btn-light mr-2 my-1 active" href="#">{{ $page }}</a></li>
                                @else
                                    <li class="page-item"><a class="page-link btn btn-icon btn-sm border-0 btn-light mr-2 my-1" href="{!! $data_target_element !!}" onclick="AJAX_HELPER.ajaxGetPaginateData('{!! $url !!}', '{!! $data_target_element !!}', {!! $page !!})">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item"><a class="page-link btn btn-icon btn-sm border-0 btn-light mr-2 my-1" href="{!! $data_target_element !!}" onclick="AJAX_HELPER.ajaxGetPaginateData('{!! $paginator->nextPageUrl() !!}', '{!! $data_target_element !!}', {!! $paginator->currentPage() + 1 !!})" aria-label="Next"><i class="flaticon2-next"></i></a></li>
                    @else
                        <li class="page-item disabled"><a class="page-link btn btn-icon btn-sm border-0 btn-light mr-2 my-1" href="#" aria-label="Next"><i class="flaticon2-next"></i></a></li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
    </div>
@endif
