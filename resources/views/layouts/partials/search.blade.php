<div class="search">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="input-wrapper">
                    <div class="input">
                        <form method="POST" action="{{ route('search') }}">
                            @csrf
                            <input aria-label="Search" type="text" name="query" placeholder="block, hash, transaction, etc...">
                            <button aria-label="Search submit" type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
