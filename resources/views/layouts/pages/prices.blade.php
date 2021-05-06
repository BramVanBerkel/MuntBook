@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Prices</h2>
            </div>
        </div>
        <div class="offset-lg-3 col-lg-6">
            <div class="center-text">
                <p>Bitrex prices</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div id="prices">

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                Timeframe: <span class="timeframe-text"></span>
            </button>
            <div class="dropdown-menu" id="timeframes">
                <h6 class="dropdown-header">Minutes</h6>
                <button class="dropdown-item timeframe" type="button"
                        value="{{ \App\Enums\PriceTimeframeEnum::MINUTE_1()->value }}"> {{ \App\Enums\PriceTimeframeEnum::MINUTE_1()->label }}
                </button>
                <button class="dropdown-item timeframe active" type="button"
                        value="{{ \App\Enums\PriceTimeframeEnum::MINUTE_5()->value }}"> {{ \App\Enums\PriceTimeframeEnum::MINUTE_5()->label }}
                </button>
                <button class="dropdown-item timeframe" type="button"
                        value="{{ \App\Enums\PriceTimeframeEnum::MINUTE_15()->value }}"> {{ \App\Enums\PriceTimeframeEnum::MINUTE_15()->label }}
                </button>
                <button class="dropdown-item timeframe" type="button"
                        value="{{ \App\Enums\PriceTimeframeEnum::MINUTE_30()->value }}"> {{ \App\Enums\PriceTimeframeEnum::MINUTE_30()->label }}
                </button>
                <h6 class="dropdown-header">Hours</h6>
                <button class="dropdown-item timeframe" type="button"
                        value="{{ \App\Enums\PriceTimeframeEnum::HOUR_1()->value }}"> {{ \App\Enums\PriceTimeframeEnum::HOUR_1()->label }}
                </button>
                <button class="dropdown-item timeframe" type="button"
                        value="{{ \App\Enums\PriceTimeframeEnum::HOUR_2()->value }}"> {{ \App\Enums\PriceTimeframeEnum::HOUR_2()->label }}
                </button>
                <button class="dropdown-item timeframe" type="button"
                        value="{{ \App\Enums\PriceTimeframeEnum::HOUR_3()->value }}"> {{ \App\Enums\PriceTimeframeEnum::HOUR_3()->label }}
                </button>
                <button class="dropdown-item timeframe" type="button"
                        value="{{ \App\Enums\PriceTimeframeEnum::HOUR_4()->value }}"> {{ \App\Enums\PriceTimeframeEnum::HOUR_4()->label }}
                </button>
                <h6 class="dropdown-header">Days</h6>
                <button class="dropdown-item timeframe" type="button"
                        value="{{ \App\Enums\PriceTimeframeEnum::DAY_1()->value }}"> {{ \App\Enums\PriceTimeframeEnum::DAY_1()->label }}
                </button>
                <button class="dropdown-item timeframe" type="button"
                        value="{{ \App\Enums\PriceTimeframeEnum::DAY_7()->value }}"> {{ \App\Enums\PriceTimeframeEnum::DAY_7()->label }}
                </button>
                <button class="dropdown-item timeframe" type="button"
                        value="{{ \App\Enums\PriceTimeframeEnum::DAY_30()->value }}"> {{ \App\Enums\PriceTimeframeEnum::DAY_30()->label }}
                </button>
            </div>
            <div id="loader" class="spinner-border text-primary align-middle d-none" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        const prices_endpoint = '{{ route('prices.data') }}';
    </script>
    <script src="{{ asset('js/prices.js') }}"></script>
@endsection
