<?php

namespace App\Enums;

enum ProductStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case OutOfStock = 'out_of_stock';
    case Discontinued = 'discontinued';
    case Banned = 'banned';
    case PendingApproval = 'pending_approval';

    /**
     * Lấy tất cả giá trị của enum
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Lấy tên hiển thị tiếng Việt
     */
    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Bản nháp',
            self::Active => 'Đang hoạt động',
            self::Inactive => 'Tạm ngưng',
            self::OutOfStock => 'Hết hàng',
            self::Discontinued => 'Ngừng kinh doanh',
            self::Banned => 'Vi phạm',
            self::PendingApproval => 'Chờ duyệt',
        };
    }

    /**
     * Lấy màu badge cho UI
     */
    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Active => 'green',
            self::Inactive => 'yellow',
            self::OutOfStock => 'orange',
            self::Discontinued => 'red',
            self::Banned => 'red',
            self::PendingApproval => 'blue',
        };
    }

    /**
     * Kiểm tra sản phẩm có thể bán không
     */
    public function isSaleable(): bool
    {
        return in_array($this, [self::Active]);
    }

    /**
     * Kiểm tra sản phẩm có hiển thị trên website không
     */
    public function isVisible(): bool
    {
        return in_array($this, [self::Active, self::OutOfStock]);
    }

    /**
     * Kiểm tra có thể chỉnh sửa không
     */
    public function isEditable(): bool
    {
        return !in_array($this, [self::Banned]);
    }

    /**
     * Lấy danh sách trạng thái có thể chuyển đến
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Draft => [self::PendingApproval, self::Inactive],
            self::PendingApproval => [self::Active, self::Inactive, self::Draft],
            self::Active => [self::Inactive, self::OutOfStock, self::Discontinued],
            self::Inactive => [self::Active, self::Draft, self::Discontinued],
            self::OutOfStock => [self::Active, self::Discontinued],
            self::Discontinued => [self::Inactive],
            self::Banned => [],
        };
    }

    /**
     * Kiểm tra có thể chuyển sang trạng thái khác không
     */
    public function canTransitionTo(self $status): bool
    {
        return in_array($status, $this->allowedTransitions());
    }

    /**
     * Lấy icon cho UI
     */
    public function icon(): string
    {
        return match ($this) {
            self::Draft => 'pencil',
            self::Active => 'check-circle',
            self::Inactive => 'pause-circle',
            self::OutOfStock => 'x-circle',
            self::Discontinued => 'archive',
            self::Banned => 'shield-x',
            self::PendingApproval => 'clock',
        };
    }

    /**
     * Lấy mô tả trạng thái
     */
    public function description(): string
    {
        return match ($this) {
            self::Draft => 'Sản phẩm đang được soạn thảo',
            self::Active => 'Sản phẩm đang bán và hiển thị trên website',
            self::Inactive => 'Sản phẩm tạm ngưng bán',
            self::OutOfStock => 'Sản phẩm tạm hết hàng',
            self::Discontinued => 'Sản phẩm ngừng kinh doanh vĩnh viễn',
            self::Banned => 'Sản phẩm vi phạm chính sách',
            self::PendingApproval => 'Sản phẩm đang chờ kiểm duyệt',
        };
    }

    /**
     * Tạo từ chuỗi với xử lý lỗi
     */
    public static function fromString(?string $value): ?self
    {
        if ($value === null) {
            return null;
        }

        return self::tryFrom($value);
    }

    /**
     * Lấy danh sách options cho select/dropdown
     */
    public static function options(): array
    {
        return array_map(
            fn(self $status) => [
                'value' => $status->value,
                'label' => $status->label(),
                'color' => $status->color(),
                'icon' => $status->icon(),
            ],
            self::cases()
        );
    }
}
