<div class="container">
    <div class="head">
        <h1>O'ZBEKISTON RESPUBLIKASI ADLIYA VAZIRLIGI</h1>
        <h2>N: <?=$licence_number?></h2>
    </div>
    <div class="body_text">
        <p style="max-width:550px;">Ushbu patent O'zbekiston Respublikasining “Ixtirolar, foydali modellar va sanoat
            namunalari
            to'g'risida”gi Qonuniga asosan quyidagi sanoat namunasiga berildi:
        </p>

        <h4 style="font-weight: bold;">Yo'naltiruvchi stela </h4>

        <div class="table" style="text-align: left">
            <div class="row">
                <div class="cell-30"><span>Talabnoma kelib tushgan sana:</span></div>
                <div class="cell-20"><span><?=$date_submitted?></span></div>
                <div class="cell-25"><span>Talabnoma raqami:</span></div>
                <div class="cell-25"><?=$licence_number?></div>
            </div>
            <div class="row">
                <div class="cell"><span>Ustuvorlik sanasi:</span></div>
                <div class="cell"><span>29.04.2022</span></div>
            </div>
            <div class="row">
                <div class="cell">Patent egasi(lar)i :</div>
                <div class="cell">  <span><?=$patent_holder?></span></div>
            </div>
            <div class="row">
                <div class="cell">Sanoat namunasi muallifi(lar)i:</div>
                <div class="cell"><?=$author?></div>
            </div>
        </div>


    </div>
    <div class="footer">
        <p style="font-size: 16px">Sanoat namunasiga berilgan patent O'zbekiston Respublikasi hududida <?=$date_submitted?> yildan boshlab patentni
            kuchda saqlab turish uchun patent boji o'z vaqtida to'langandagina 10 yil mobaynida amal qiladi.
            <br>
            O'zbekiston Respublikasi Sanoat namunalari davlat reyestrida <?=$date_submitted?> yilda ro'yxatdan o'tkazildi.
        </p>
    </div>
    <div class="qrCode">
        <img src="" width="150px" height="150px">
    </div>
</div>