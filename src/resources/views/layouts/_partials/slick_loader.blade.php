@section ('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.slider-for').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: true,
                fade: true,
                dots: true,
                respondTo: window
            });
        });
    </script>
@endsection