<div class="title flex flex-between flex-align-center">
    <span>Реферальная система</span>
</div>

<div class="content">
    <div class="alert">
        <span>Активируйте Реферальный/Промо код и получите 5 рублей на баланс.</span>
        <span class="alert-close tooltip" data-close="alert" title="Закрыть">×</span>
    </div>
        <div class="refferal_content">
        <div class="block">
            <div class="title">
                 Активировать реф/промо код
            </div>
            <div class="input">
                <input type="text" class="promoCode" placeholder="Введите код...">
                <input type="submit" class="promoButton" value="Активировать">
            </div>
            <div class="descr">
                <p>Промо-коды в нашей группе.</p>
            </div>
        </div>
        <div class="block">
            <div class="title">
                Ваш код для приглашения:
            </div>
            <div class="input">
                <input type="text" value="{{$u->affiliate_id}}" id="code" readonly>
                <input type="submit" value="Копировать" onclick="$.copyToClipboard('#code')">
            </div>
            <div class="descr">
                @if($ref)
                <p class="desc">Вы пригласили {{$ref}} человек и получаете {{$perc}}% от выиграных ставок.</p>
                @else
                <p class="desc">Еще ни кто не ввел Ваш реферальный код.</p>
                @endif
            </div>
        </div>
        <div class="block">
            <div class="title">
                Ваш реферальный уровень
            </div>
            <div class="line_ref">
                <div class="line">
                    <div id="line" style="width: {{$width}}%"></div>
                </div>
                <div class="circle l-c1 checked">{{$lvl}}</div>
            </div>
            <div class="info">
                <span>Доступно для получения: <b>{{floor($u->ref_money)}} <i class="fas fa-coins"></i></b></span>
                <span>Всего заработано: <b>{{floor($u->ref_money_history)}} <i class="fas fa-coins"></i></b></span>
                @if($u->ref_money > 0.99)
                <a href="#" class="take getMoney">Забрать</a>
                @endif
            </div>
        </div>
    </div>

</div>