@if ($type == "link-status")
    @piechart('Chart', 'chart')
@elseif ($type == "from-url")
    @piechart('Chart', 'chart')
@elseif ($type == "bldom")
    @barchart('Chart', 'chart')
@elseif ($type == "anchor-text")
    @foreach ($chart as $link)
        <span class="tag {{ $link->display_class }}">{{ $link->_id }}</span>
    @endforeach
@endif