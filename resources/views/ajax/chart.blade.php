@if ($type == "link-status")
    @piechart('Chart', 'chart')
@elseif ($type == "from-url")
    @piechart('Chart', 'chart')
@elseif ($type == "bldom")
    @barchart('Chart', 'chart')
@endif