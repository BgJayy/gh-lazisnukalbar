document.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-copy]');

    if (! button) {
        return;
    }

    const value = button.getAttribute('data-copy') ?? '';
    const copied = await copyText(value);

    if (! copied) {
        return;
    }

    const previous = button.textContent;
    button.textContent = 'Tersalin';
    window.setTimeout(() => {
        button.textContent = previous;
    }, 1500);
});

document.querySelectorAll('[data-amount]').forEach((chip) => {
    chip.addEventListener('click', () => {
        const input = document.querySelector('[data-amount-input]');

        if (! input) {
            return;
        }

        input.value = chip.getAttribute('data-amount') ?? '';
        document.querySelectorAll('[data-amount]').forEach((item) => {
            item.setAttribute('aria-pressed', item === chip ? 'true' : 'false');
        });
    });
});

const channelSelect = document.querySelector('[data-channel-select]');
const bookLabel = document.querySelector('[data-book-label]');

if (channelSelect && bookLabel) {
    const syncBook = () => {
        const option = channelSelect.selectedOptions[0];
        bookLabel.textContent = option?.dataset.book || bookLabel.textContent;
    };

    channelSelect.addEventListener('change', syncBook);
}

const donorName = document.querySelector('[data-donor-name]');
const donorAnon = document.querySelector('[data-anon]');
const donorPreview = document.querySelector('[data-donor-preview]');

if (donorName && donorAnon && donorPreview) {
    const syncDonor = () => {
        const name = donorName.value.trim();
        donorPreview.textContent = donorAnon.checked || name === '' ? 'Hamba Allah' : name;
    };

    donorName.addEventListener('input', syncDonor);
    donorAnon.addEventListener('change', syncDonor);
    syncDonor();
}

const digits = (value) => Number(String(value ?? '').replace(/[^\d]/g, '') || 0);

const payHref = (base, channel, amount) => {
    const url = new URL(base, window.location.origin);
    url.searchParams.set('channel', channel);
    url.searchParams.set('amount', String(amount));

    return `${url.pathname}${url.search}`;
};

const bindNominalPay = (root) => {
    const input = root.querySelector('[data-pay-amount]');
    const select = root.querySelector('[data-pay-channel]');
    const link = root.querySelector('[data-pay-link]');
    const note = root.querySelector('[data-pay-note]');

    if (! input || ! link || ! root.dataset.base) {
        return;
    }

    const sync = () => {
        const amount = digits(input.value);
        const channel = select?.value || '';

        if (amount >= 10000 && amount <= 1000000000 && channel !== '') {
            link.href = payHref(root.dataset.base, channel, amount);
            link.removeAttribute('aria-disabled');
            link.classList.remove('pointer-events-none', 'opacity-50');
            if (note) {
                note.hidden = true;
            }

            return;
        }

        link.removeAttribute('href');
        link.setAttribute('aria-disabled', 'true');
        link.classList.add('pointer-events-none', 'opacity-50');

        if (! note) {
            return;
        }

        note.hidden = amount === 0;
        note.textContent = amount > 1000000000
            ? 'Nominal melebihi batas pembayaran di situs ini.'
            : 'Nominal minimal Rp10.000.';
    };

    input.addEventListener('input', sync);
    select?.addEventListener('change', sync);

    if (select && (location.hash === '#fidyah' || location.hash === '#qurban')) {
        select.value = location.hash.slice(1);
    }

    sync();
};

document.querySelectorAll('[data-ziswaf-pay]').forEach(bindNominalPay);

const calculator = document.querySelector('[data-zakat-calculator]');

if (calculator) {
    const typeSelect = calculator.querySelector('[data-zakat-type]');
    const goldInput = calculator.querySelector('[data-gold]');
    const known = calculator.querySelector('[data-zakat-known]');
    const knownWrap = calculator.querySelector('[data-zakat-known-wrap]');
    const fieldLabel = calculator.querySelector('[data-zakat-field-label]');
    const result = calculator.querySelector('[data-zakat-result]');
    const note = calculator.querySelector('[data-zakat-note]');
    const pay = calculator.querySelector('[data-zakat-pay]');
    const inputs = {
        income: calculator.querySelector('[data-zakat-field="income"]'),
        wealth: calculator.querySelector('[data-zakat-field="wealth"]'),
        emas: calculator.querySelector('[data-zakat-field="emas"]'),
        manual: calculator.querySelector('[data-zakat-field="manual"]'),
    };
    const autoTypes = ['profesi', 'maal', 'uang', 'emas'];
    const channels = {
        profesi: 'zakat_penghasilan',
        maal: 'zakat_maal',
        fitrah: 'zakat_fitrah',
        emas: 'zakat_emas',
        uang: 'zakat_uang',
        perniagaan: 'zakat_perniagaan',
        pertambangan: 'zakat_pertambangan',
        pertanian: 'zakat_pertanian',
        peternakan: 'zakat_peternakan',
    };
    const labels = {
        profesi: 'Penghasilan bulan ini',
        maal: 'Kekayaan (1 tahun)',
        uang: 'Uang dan surat berharga',
        emas: 'Emas (gram)',
        fitrah: 'Nominal zakat',
        perniagaan: 'Nominal zakat',
        pertambangan: 'Nominal zakat',
        pertanian: 'Nominal zakat',
        peternakan: 'Nominal zakat',
    };
    const money = (value) => Math.round(value).toLocaleString('id-ID');

    const showPay = (amount, channel) => {
        result.textContent = money(Math.max(0, amount));

        if (! pay) {
            return;
        }

        if (amount >= 10000 && amount <= 1000000000) {
            pay.href = payHref(pay.dataset.base, channel, amount);
            pay.hidden = false;

            return;
        }

        pay.hidden = true;
        pay.removeAttribute('href');

        if (amount > 1000000000) {
            note.textContent = 'Nominal melebihi batas pembayaran di situs ini.';
        } else if (amount > 0 && amount < 10000) {
            note.textContent = 'Nominal minimal untuk pembayaran di situs ini Rp10.000.';
        }
    };

    const render = () => {
        if (! typeSelect || ! goldInput || ! result || ! note) {
            return;
        }

        const type = typeSelect.value;
        const manualOnly = ! autoTypes.includes(type);
        const useManual = manualOnly || Boolean(known?.checked);
        const fieldFor = { profesi: 'income', maal: 'wealth', uang: 'wealth', emas: 'emas' };
        const active = useManual ? 'manual' : (fieldFor[type] ?? 'manual');
        const gold = digits(goldInput.value);
        const channel = channels[type] ?? 'zakat_maal';

        Object.entries(inputs).forEach(([key, input]) => {
            if (! input) {
                return;
            }

            input.hidden = key !== active;
        });

        if (knownWrap) {
            knownWrap.hidden = manualOnly;
        }

        if (known && manualOnly) {
            known.checked = false;
        }

        if (fieldLabel) {
            fieldLabel.textContent = useManual ? 'Nominal zakat' : (labels[type] ?? 'Nominal zakat');
        }

        if (useManual) {
            const amount = digits(inputs.manual?.value);
            note.textContent = amount > 0 && amount < 10000 ? 'Nominal minimal Rp10.000.' : '';
            showPay(amount, channel);

            return;
        }

        if (gold <= 0) {
            note.textContent = 'Isi harga emas per gram untuk menghitung nisab 85 gram.';
            showPay(0, channel);

            return;
        }

        const nisabYear = 85 * gold;

        if (type === 'emas') {
            const grams = digits(inputs.emas?.value);
            note.textContent = `Nisab emas 85 gram. Perak dan logam mulia dihitung lewat nominal yang sudah diketahui.`;

            if (grams <= 0) {
                showPay(0, channel);

                return;
            }

            if (grams < 85) {
                note.textContent = 'Nisab emas 85 gram belum tercapai.';
                showPay(0, channel);

                return;
            }

            showPay(Math.round(grams * gold * 0.025), channel);

            return;
        }

        const wealth = digits(type === 'profesi' ? inputs.income?.value : inputs.wealth?.value);
        const threshold = type === 'profesi' ? nisabYear / 12 : nisabYear;
        note.textContent = type === 'profesi'
            ? `Nisab bulan ini Rp${money(threshold)}.`
            : `Nisab setahun Rp${money(nisabYear)}.`;

        if (wealth <= 0) {
            showPay(0, channel);

            return;
        }

        if (wealth < threshold) {
            note.textContent = 'Nisab belum tercapai. Zakat belum wajib.';
            showPay(0, channel);

            return;
        }

        showPay(Math.round(wealth * 0.025), channel);
    };

    typeSelect?.addEventListener('change', render);
    goldInput?.addEventListener('input', render);
    known?.addEventListener('change', render);
    Object.values(inputs).forEach((input) => input?.addEventListener('input', render));
    render();
}

document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (form.dataset.submitted === '1') {
            event.preventDefault();

            return;
        }

        form.dataset.submitted = '1';
        form.querySelectorAll('button[type="submit"]').forEach((button) => {
            button.disabled = true;
        });
    });
});

async function copyText(value) {
    try {
        await navigator.clipboard.writeText(value);

        return true;
    } catch {
        const area = document.createElement('textarea');
        area.value = value;
        area.setAttribute('readonly', '');
        area.style.position = 'fixed';
        area.style.left = '-9999px';
        document.body.appendChild(area);
        area.select();
        const ok = document.execCommand('copy');
        area.remove();

        return ok;
    }
}
