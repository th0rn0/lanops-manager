@extends ('layouts.default')

@section('page_title', Settings::getOrgName() . ' - ' . __('help.help'))


@section('scripts')
    <script>
        function copyTextToClipBoard(copyText) {
            const el = document.createElement('textarea');
            el.value = copyText;
            el.setAttribute('readonly', '');
            el.style.position = 'absolute';
            el.style.left = '-9999px';
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
        }
        document.addEventListener("DOMContentLoaded", function(event) {
            $("#searchtext").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#helpentries .accordion-item").filter(function() {
                    $(this).toggle($(this).html().toLowerCase().indexOf(value) > -1)
                });
                // fix automatic borders with bootstrap 5 accordion
                $('#helpentries').find('.accordion-item:visible:first').css("border-top",
                    "var(--bs-accordion-border-width) solid var(--bs-accordion-border-color)").css(
                    "border-top-left-radius", "var(--bs-accordion-border-radius)").css(
                    "border-top-right-radius", "var(--bs-accordion-border-radius)");
                $('#helpentries').find('.accordion-item:visible:not(:first)').css("border-top",
                    "0").css(
                    "border-top-left-radius", "0").css(
                    "border-top-right-radius", "0");

            });
        });
    </script>
@endsection

@section('content')

    <div class="container pt-1">

        <div class="pb-2 mt-4 mb-4 border-bottom">
            <h1>@lang('help.help')</h1>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="search-addon"><i class="fas fa-search"></i></span>
            <input id="searchtext" type="text" class="form-control" placeholder="@lang('help.search')" aria-label="searchtext"
                aria-describedby="search-addon">
        </div>

        <div class="accordion " id="helpentries">
            @foreach ($helpCategorys as $helpCategory)
                @foreach ($helpCategory->entrys as $entry)
                    <div class="accordion-item @if (Colors::isBodyDarkMode()) border-light @endif">

                        <div class="accordion-header" role="tab" id="{{ $entry->nice_name }}">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a class="btn btn-link justify-content-md-end" type="button"
                                    onclick="copyTextToClipBoard('{{ url()->full() }}#{{ $entry->nice_name }}')"
                                    title="@lang('help.copylink')"><i class="far fa-clipboard"></i></a>
                            </div>
                            <a class="accordion-button accordion-arrow-toggle @if (($loop->parent->first && !$loop->first) || !$loop->parent->first) ) collapsed @endif"
                                role="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $entry->nice_name }}"
                                href="#collapse_{{ $entry->nice_name }}"
                                aria-expanded=@if (($loop->parent->first && !$loop->first) || !$loop->parent->first) ) "false" @else "true" @endif
                                aria-controls="collapse_{{ $entry->nice_name }}">
                                <h4 class="card-title m-0 d-inline flex-grow-1">
                                    {{ $entry->display_name }}
                                </h4>
                                <div class="d-flex align-items-center">
                                    <span class="badge text-bg-info">{{ $helpCategory->name }}</span>
                                    @if ($entry->hasAttachment())
                                        <span class="badge text-bg-info">@lang('help.attachment')</span>
                                    @endif
                                </div>
                            </a>
                        </div>

                        <div id="collapse_{{ $entry->nice_name }}"
                            class="accordion-collapse @if (($loop->parent->first && !$loop->first) || !$loop->parent->first) ) collapse @endif"
                            data-bs-parent="#helpentries">
                            <div class="accordion-body">
                                {!! $entry->content !!}
                                <div>
                                    <table width="100%" class="table" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>@lang('help.filename')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($entry->attachments as $attachment)
                                                <tr>
                                                    <td>
                                                        <a
                                                            href="{{ $attachment->path }}">{{ $attachment->display_name }}</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

    </div>


@endsection
