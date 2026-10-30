<?php

namespace App\Enums;

use App\Support\HasOptions;
use Filament\Support\Contracts\HasLabel;

enum DonationChannel: string implements HasLabel
{
    use HasOptions;

    case ZakatPenghasilan = 'zakat_penghasilan';
    case ZakatMaal = 'zakat_maal';
    case ZakatFitrah = 'zakat_fitrah';
    case ZakatEmas = 'zakat_emas';
    case ZakatUang = 'zakat_uang';
    case ZakatPerniagaan = 'zakat_perniagaan';
    case ZakatPertambangan = 'zakat_pertambangan';
    case ZakatPertanian = 'zakat_pertanian';
    case ZakatPeternakan = 'zakat_peternakan';
    case Infak = 'infak';
    case Sedekah = 'sedekah';
    case Fidyah = 'fidyah';
    case Qurban = 'qurban';
    case QuickInfak = 'quick_infak';
    case Wakaf = 'wakaf';
    case Campaign = 'campaign';

    public function requiredBook(): ?FundBook
    {
        return match ($this) {
            self::ZakatPenghasilan, self::ZakatMaal, self::ZakatFitrah, self::ZakatEmas, self::ZakatUang, self::ZakatPerniagaan, self::ZakatPertambangan, self::ZakatPertanian, self::ZakatPeternakan => FundBook::Zakat,
            self::Infak, self::Sedekah, self::Fidyah, self::Qurban, self::QuickInfak => FundBook::InfakSedekah,
            self::Wakaf => FundBook::Wakaf,
            self::Campaign => null,
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::ZakatPenghasilan => 'Zakat penghasilan',
            self::ZakatMaal => 'Zakat maal',
            self::ZakatFitrah => 'Zakat fitrah',
            self::ZakatEmas => 'Zakat emas',
            self::ZakatUang => 'Zakat uang',
            self::ZakatPerniagaan => 'Zakat perniagaan',
            self::ZakatPertambangan => 'Zakat pertambangan',
            self::ZakatPertanian => 'Zakat pertanian',
            self::ZakatPeternakan => 'Zakat peternakan',
            self::Infak => 'Infak',
            self::Sedekah => 'Sedekah',
            self::Fidyah => 'Fidyah',
            self::Qurban => 'Qurban',
            self::QuickInfak => 'QuickInfak',
            self::Wakaf => 'Wakaf',
            self::Campaign => 'Kampanye',
        };
    }
}
