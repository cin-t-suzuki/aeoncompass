      <div class="snv-text">
        <ul class="snv-text-l5">
           @if ($current == "hotel"  )<li class="current">宿泊施設関係者様へ</li> @else <li><a href="{$v->env.ssl_path}contact/hotel/">宿泊施設関係者様へ</a></li> @endif
           @if ($current == "partner")<li class="current">業務提携について</li> @else <li><a href="{$v->env.path_base}/contact/partner/">業務提携について</a></li> @endif
           @if ($current == "recruit")<li class="current">人材募集</li> @else <li><a href="{$v->env.path_base}/about/recruit/">人材募集</a></li> @endif
           @if ($current == "about"  )<li class="current">会社概要</li> @else <li><a href="{$v->env.path_base}/about/">会社概要</a></li> @endif
        </ul>
      </div>
