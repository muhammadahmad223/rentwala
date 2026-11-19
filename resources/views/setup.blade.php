<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#f2f5f6">
    <title>Set up your account · RentWala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{--ink:#10343a;--brand:#0b6e6e;--bg:#f2f5f6}
        *{box-sizing:border-box}
        body{margin:0;background:var(--bg);color:var(--ink);font-family:system-ui,-apple-system,"Segoe UI",sans-serif;min-height:100vh;min-height:100svh;padding-bottom:env(safe-area-inset-bottom)}
        .setup-shell{max-width:720px;margin:0 auto;padding:24px 16px 36px}
        .setup-brand{font-weight:800;letter-spacing:-.04em;font-size:1.15rem;margin-bottom:28px}
        .intro{margin-bottom:22px}.intro h1{font-size:clamp(1.55rem,6vw,2rem);font-weight:800;letter-spacing:-.04em;margin:0 0 6px}.intro p{color:#5c7074;margin:0}
        .progress-track{height:7px;border-radius:9px;background:#d9e3e4;overflow:hidden;margin:18px 0 9px}.progress-bar{height:100%;width:33.333%;border-radius:9px;background:var(--brand);transition:width .2s ease}
        .progress-copy{display:flex;justify-content:space-between;color:#52696d;font-size:.88rem;margin-bottom:16px}
        .step-card{background:white;border:1px solid #d3dee0;border-radius:18px;padding:22px 18px;box-shadow:0 8px 25px #10343a0b}
        .step-title{font-size:1.15rem;font-weight:750;line-height:1.35;margin-bottom:18px}
        .form-control{min-height:54px;border-radius:12px;border-color:#cbd8da;font-size:1rem}.form-control:focus{border-color:var(--brand);box-shadow:0 0 0 .2rem #0b6e6e20}
        .btn{min-height:52px;border-radius:12px;font-weight:700;padding:10px 18px}.btn-brand{background:var(--brand);color:#fff;border:0}.btn-brand:hover{background:#09595a;color:#fff}
        .choice{position:absolute;opacity:0;width:1px;height:1px}.choice-card{display:flex;align-items:flex-start;gap:13px;min-height:76px;padding:16px;border:2px solid #d3dee0;border-radius:14px;background:#fff;cursor:pointer;transition:border-color .15s,background .15s}.choice-card:hover{border-color:#7aa9a8}.choice:checked+.choice-card{border-color:var(--brand);background:#eaf6f3}.choice:focus-visible+.choice-card{outline:3px solid #0b6e6e55;outline-offset:2px}.choice-icon{flex:none;width:40px;height:40px;border-radius:12px;background:#e7f1ef;display:grid;place-items:center;font-size:1.15rem}.choice-name{display:block;font-weight:750}.choice-help{display:block;color:#5c7074;font-size:.9rem;line-height:1.4;margin-top:2px}
        .space-row{border:1px solid #d3dee0;border-radius:14px;padding:15px;background:#fff}.step-actions{display:flex;gap:10px;margin-top:20px}.step-actions .btn{flex:1}.error-note{color:#b42318;font-size:.9rem;margin-top:8px}
        @media(min-width:576px){.setup-shell{padding:36px 22px}.step-card{padding:28px}.setup-brand{margin-bottom:36px}.step-actions .btn{flex:0 1 190px}}
    </style>
</head>
<body>
<main class="setup-shell">
    <div class="setup-brand">RentWala</div>
    <header class="intro"><h1>Set up your account</h1><p>Three quick steps. You can change these details later.</p></header>
    <div class="progress-track" role="progressbar" aria-label="Setup progress" aria-valuemin="1" aria-valuemax="3" aria-valuenow="1"><div class="progress-bar" id="progress"></div></div>
    <div class="progress-copy"><span id="step-label">Step 1 of 3</span><span id="step-hint">Business name</span></div>
    @if($errors->any())<div class="alert alert-danger rounded-3" role="alert">{{ $errors->first() }}</div>@endif
    <form method="POST" action="/setup" id="setup-form">@csrf
        <section class="step step-card" aria-labelledby="title-1">
            <h2 class="step-title" id="title-1" tabindex="-1">What should we call your property?</h2>
            <label class="form-label fw-semibold" for="bname">Property or hostel name</label>
            <input id="bname" name="business_name" class="form-control" placeholder="e.g. Al-Madina Boys Hostel" value="{{ old('business_name') }}" maxlength="80" autocomplete="organization" required>
            <div class="step-actions"><button type="button" class="btn btn-brand" onclick="nextStep()">Continue <span aria-hidden="true">→</span></button></div>
        </section>
        <section class="step step-card d-none" aria-labelledby="title-2" aria-hidden="true" inert>
            <h2 class="step-title" id="title-2" tabindex="-1">What do you rent out?</h2>
            <div class="d-grid gap-2">
                <div><input class="choice" type="radio" name="business_type" id="t1" value="hostel" {{ old('business_type') === 'hostel' ? 'checked' : '' }}><label class="choice-card" for="t1"><span class="choice-icon" aria-hidden="true">⌂</span><span><span class="choice-name">Hostel</span><span class="choice-help">Rooms with beds shared by multiple people.</span></span></label></div>
                <div><input class="choice" type="radio" name="business_type" id="t2" value="house" {{ old('business_type') === 'house' ? 'checked' : '' }}><label class="choice-card" for="t2"><span class="choice-icon" aria-hidden="true">⌂</span><span><span class="choice-name">Houses</span><span class="choice-help">Separate houses rented to families or individuals.</span></span></label></div>
                <div><input class="choice" type="radio" name="business_type" id="t3" value="building" {{ old('business_type') === 'building' ? 'checked' : '' }}><label class="choice-card" for="t3"><span class="choice-icon" aria-hidden="true">▦</span><span><span class="choice-name">Flats or shops</span><span class="choice-help">Units in a building, such as flats or shops.</span></span></label></div>
            </div>
            <p class="error-note d-none" id="choice-error" role="alert">Choose an option to continue.</p>
            <div class="step-actions"><button type="button" class="btn btn-outline-secondary" onclick="showStep(1)">Back</button><button type="button" class="btn btn-brand" onclick="nextStep()">Continue <span aria-hidden="true">→</span></button></div>
        </section>
        <section class="step step-card d-none" aria-labelledby="q" aria-hidden="true" inert>
            <h2 class="step-title" id="q" tabindex="-1"></h2>
            <label class="form-label fw-semibold" for="count">How many do you have?</label>
            <input id="count" type="number" min="1" max="200" value="{{ old('names') ? count(old('names')) : 1 }}" class="form-control mb-3" inputmode="numeric" oninput="sync()">
            <div id="rows" class="d-grid gap-2"></div>
            <div class="step-actions"><button type="button" class="btn btn-outline-secondary" onclick="showStep(2)">Back</button><button class="btn btn-brand" type="submit">Finish setup</button></div>
        </section>
    </form>
</main>
<script>
const typeDetails={hostel:{question:'How many rooms does your hostel have?',name:'Room',rent:'Rent per bed (Rs)'},house:{question:'How many houses do you rent out?',name:'House',rent:'Rent per month (Rs)'},building:{question:'How many flats or shops do you rent out?',name:'Unit',rent:'Rent per month (Rs)'}};
const steps=[...document.querySelectorAll('.step')];
const previousNames=@json(old('names', []));
const previousBeds=@json(old('beds', []));
const previousRents=@json(old('rents', []));
let currentStep=1;
const selectedType=()=>document.querySelector('[name="business_type"]:checked')?.value;
function showStep(step){
    currentStep=step;
    steps.forEach((section,index)=>{const active=index===step-1;section.classList.toggle('d-none',!active);section.setAttribute('aria-hidden',String(!active));section.toggleAttribute('inert',!active)});
    document.getElementById('step-label').textContent=`Step ${step} of 3`;
    document.getElementById('step-hint').textContent=['Business name','Rental type','Your spaces'][step-1];
    document.getElementById('progress').style.width=`${step/3*100}%`;
    document.querySelector('[role="progressbar"]').setAttribute('aria-valuenow',String(step));
    document.querySelector(`#title-${step}`)?.focus({preventScroll:true});
    window.scrollTo({top:0,behavior:'smooth'});
}
function nextStep(){
    if(currentStep===1&&!document.getElementById('bname').reportValidity())return;
    if(currentStep===2){
        if(!selectedType()){document.getElementById('choice-error').classList.remove('d-none');document.querySelector('.choice').focus();return}
        document.getElementById('choice-error').classList.add('d-none');
        document.getElementById('q').textContent=typeDetails[selectedType()].question;
        document.getElementById('rows').replaceChildren();
        sync();
    }
    showStep(currentStep+1);
}
function sync(){
    const details=typeDetails[selectedType()];
    const count=Math.min(200,Math.max(1,Number(document.getElementById('count').value)||1));
    const rows=document.getElementById('rows');
    while(rows.children.length>count)rows.lastElementChild.remove();
    while(rows.children.length<count){
        const index=rows.children.length+1;
        const row=document.createElement('div');
        row.className='space-row';
        row.innerHTML=`<div class="row g-3"><div class="col-12"><label class="form-label" for="space-name-${index}">${details.name} name</label><input id="space-name-${index}" class="form-control" name="names[]" value="${details.name} ${index}" maxlength="60" required></div>${selectedType()==='hostel'?`<div class="col-6"><label class="form-label" for="space-beds-${index}">Beds</label><input id="space-beds-${index}" type="number" min="1" max="50" class="form-control" name="beds[]" value="3" required></div>`:''}<div class="col-6"><label class="form-label" for="space-rent-${index}">${details.rent}</label><input id="space-rent-${index}" type="number" min="0" class="form-control" name="rents[]" value="0" required></div></div>`;
        row.querySelector('[name="names[]"]').value=previousNames[index-1]??`${details.name} ${index}`;
        row.querySelector('[name="rents[]"]').value=previousRents[index-1]??0;
        const bedsInput=row.querySelector('[name="beds[]"]');
        if(bedsInput)bedsInput.value=previousBeds[index-1]??3;
        rows.appendChild(row);
    }
}
document.querySelectorAll('[name="business_type"]').forEach(input=>input.addEventListener('change',()=>document.getElementById('choice-error').classList.add('d-none')));
@if(old('business_type'))
document.getElementById('q').textContent=typeDetails[selectedType()].question;
sync();
showStep(3);
@endif
</script>
</body>
</html>
