<?php

namespace Litepie\Form\Concerns;

/**
 * Handles field layout, positioning, and grouping
 */
trait HasLayout
{
    /**
     * Field width in grid columns.
     */
    protected ?int $width = null;

    /**
     * Total columns in grid (default: 12).
     */
    protected int $totalColumns = 12;

    /**
     * Default width if not specified.
     */
    protected static int $defaultWidth = 6;

    /**
     * Row identifier for grouping fields.
     */
    protected ?string $row = null;

    /**
     * Group identifier (top-level grouping).
     */
    protected ?string $group = null;

    /**
     * Section identifier (sub-group within a group).
     */
    protected ?string $section = null;

    /**
     * Number of columns for layout.
     */
    protected ?int $columns = null;

    /**
     * Set field width in columns.
     */
    public function width(int $columns, int $total = 12): self
    {
        $this->width = $columns;
        $this->totalColumns = $total;
        return $this;
    }

    /**
     * Set field column span (shorthand for width).
     */
    public function col(int $columns): self
    {
        return $this->width($columns, 12);
    }

    /**
     * Get field width (returns default if not set).
     */
    public function getWidth(): int
    {
        return $this->width ?? self::$defaultWidth;
    }

    /**
     * Get total columns in grid.
     */
    public function getTotalColumns(): int
    {
        return $this->totalColumns;
    }

    /**
     * Set default width for all fields.
     */
    public static function setDefaultWidth(int $width): void
    {
        self::$defaultWidth = $width;
    }

    /**
     * Get default width.
     */
    public static function getDefaultWidth(): int
    {
        return self::$defaultWidth;
    }

    /**
     * Set row identifier for grouping.
     */
    public function row(string $row): self
    {
        $this->row = $row;
        return $this;
    }

    /**
     * Get row identifier.
     */
    public function getRow(): ?string
    {
        return $this->row;
    }

    /**
     * Set group identifier.
     */
    public function group(string $group): self
    {
        $this->group = $group;
        return $this;
    }

    /**
     * Get group identifier.
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * Set section identifier.
     */
    public function section(string $section): self
    {
        $this->section = $section;
        return $this;
    }

    /**
     * Get section identifier.
     */
    public function getSection(): ?string
    {
        return $this->section;
    }

    /**
     * Set number of columns for layout.
     */
    public function columns(int|array $columns): self
    {
        $this->columns = is_array($columns) ? $columns : $columns;
        return $this;
    }

    /**
     * Get columns.
     */
    public function getColumns(): ?int
    {
        return $this->columns;
    }
}
