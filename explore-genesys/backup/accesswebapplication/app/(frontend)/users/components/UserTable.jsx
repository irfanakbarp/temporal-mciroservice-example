"use client"

import * as React from "react"
import {
    flexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useReactTable,
} from "@tanstack/react-table"
import { ArrowUpDown, ChevronDown, MoreHorizontal, Phone } from "lucide-react"


import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu"
import { Button } from "@/components/ui/button"

import { Input } from "@/components/ui/input"
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table"
import Link from "next/link"

const data = [
    {
        id: "m5gr84i9",
        name: "Ken",
        phone: "08212345671",
        status: "active",
        email: "ken99@example.com",
        createdAt: "2024-08-23T10:30:00.000Z", // ISO 8601 format from Java LocalDateTime
    },
    {
        id: "3u1reuv4",
        name: "Jovany",
        phone: "08212345672",
        status: "active",
        email: "Abe45@example.com",
        createdAt: "2024-07-23T10:30:00.000Z", // ISO 8601 format from Java LocalDateTime
    },
    {
        id: "derv1ws0",
        name: "donald",
        phone: "08212345673",
        status: "active",
        email: "Monserrat44@example.com",
        createdAt: "2024-06-23T10:30:00.000Z", // ISO 8601 format from Java LocalDateTime
    },
    {
        id: "5kma53ae",
        name: "Silas",
        phone: "08212345674",
        status: "active",
        email: "Silas22@example.com",
        createdAt: "2024-05-23T10:30:00.000Z", // ISO 8601 format from Java LocalDateTime
    },
    {
        id: "bhqecj4p",
        name: "alvin",
        phone: "08212345675",
        status: "active",
        email: "carmella@example.com",
        createdAt: "2024-04-23T10:30:00.000Z", // ISO 8601 format from Java LocalDateTime
    },
    {
        id: "bhqecj4p",
        name: "alvin",
        phone: "08212345676",
        status: "inactive",
        email: "carmella@example.com",
        createdAt: "2024-03-23T10:30:00.000Z", // ISO 8601 format from Java LocalDateTime
    },
    {
        id: "bhqecj4p",
        name: "alvin",
        phone: "08212345677",
        status: "inactive",
        email: "carmella@example.com",
        createdAt: "2024-02-23T10:30:00.000Z", // ISO 8601 format from Java LocalDateTime
    },
    {
        id: "bhqecj4p",
        name: "alvin",
        phone: "08212345678",
        status: "inactive",
        email: "carmella@example.com",
        createdAt: "2024-01-23T10:30:00.000Z", // ISO 8601 format from Java LocalDateTime
    },
]

export const columns = [
    {
        accessorKey: "name",
        header: () => <div className="w-full text-center">Name</div>,
        cell: ({ row }) => <div className="lowercase">{row.getValue("name")}</div>,
    },
    {
        accessorKey: "email",
        header: ({ column }) => {
            return (
                <Button
                    variant="ghost"
                    onClick={() => column.toggleSorting(column.getIsSorted() === "asc")}
                    className="w-full text-center px-10 cursor-pointer"
                >
                    Email
                    <ArrowUpDown />
                </Button>
            )
        },
        cell: ({ row }) => <div className="lowercase">{row.getValue("email")}</div>,
    },
    {
        accessorKey: "phone",
        header: ({ column }) => {
            return (
                <Button
                    variant="ghost"
                    onClick={() => column.toggleSorting(column.getIsSorted() === "asc")}
                    className="w-full text-center px-10 cursor-pointer"
                >
                    Phone
                    <ArrowUpDown />
                </Button>
            )
        },
        cell: ({ row }) =>
            <Link href={`/users/${row.getValue("phone")}`} className="w-fit hover:underline flex items-center gap-2">
                <Phone size={16} className="text-slate-500" />
                <div className="lowercase">{row.getValue("phone")}</div>
            </Link>
        ,
    },
    {
        accessorKey: "status",
        header: () => <div className="w-full text-center">Status</div>,
        cell: ({ row }) => {
            const status = row.getValue("status")
            return (
                <span className={`px-2 py-1 rounded-full text-xs font-medium capitalize ${status === "active"
                    ? "bg-green-100 text-green-800"
                    : status === "inactive"
                        ? "bg-red-100 text-red-800"
                        : "bg-gray-100 text-gray-800"
                    }`}>
                    {status}
                </span>
            )
        },
    },
    {
        accessorKey: "createdAt",
        header: ({ column }) => {
            return (
                <Button
                    variant="ghost"
                    onClick={() => column.toggleSorting(column.getIsSorted() === "asc")}
                    className="w-full text-center px-10 cursor-pointer"
                >
                    Created at
                    <ArrowUpDown />
                </Button>
            )
        },
        cell: ({ row }) => {
            const date = new Date(row.getValue("createdAt"))
            return date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            })
        },
    },
    {
        id: "actions",
        enableHiding: false,
        cell: ({ row }) => {
            const users = row.original

            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="ghost" className="h-8 w-full justify-center p-0">
                            <span className="sr-only">Open menu</span>
                            <MoreHorizontal />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>Actions</DropdownMenuLabel>
                        <DropdownMenuItem
                            onClick={() => navigator.clipboard.writeText(users.phone)}
                        >
                            Copy phone number
                        </DropdownMenuItem>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem><Link href={`/users/${users.phone}`}>View user detail</Link></DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            )
        },
    },
]

export function UserTable() {
    const [isClient, setIsClient] = React.useState(false);
    const [sorting, setSorting] = React.useState([]);
    const [columnFilters, setColumnFilters] = React.useState([]);
    const [columnVisibility, setColumnVisibility] =
        React.useState({})
    const [rowSelection, setRowSelection] = React.useState({})

    const table = useReactTable({
        data,
        columns,
        onSortingChange: setSorting,
        onColumnFiltersChange: setColumnFilters,
        getCoreRowModel: getCoreRowModel(),
        getPaginationRowModel: getPaginationRowModel(),
        getSortedRowModel: getSortedRowModel(),
        getFilteredRowModel: getFilteredRowModel(),
        onColumnVisibilityChange: setColumnVisibility,
        onRowSelectionChange: setRowSelection,
        state: {
            sorting,
            columnFilters,
            columnVisibility,
            rowSelection,
        },
    })

    React.useEffect(() => {
        setIsClient(true);
    }, []);

    if (!isClient) {
        return null; // Prevent rendering if not client-side
    }

    return (
        <div className="w-full">
            <div className="flex items-center py-4">
                <Input
                    placeholder="Filter emails..."
                    value={(table.getColumn("email")?.getFilterValue()) ?? ""}
                    onChange={(event) =>
                        table.getColumn("email")?.setFilterValue(event.target.value)
                    }
                    className="max-w-sm"
                />
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="outline" className="ml-auto">
                            Columns <ChevronDown />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        {table
                            .getAllColumns()
                            .filter((column) => column.getCanHide())
                            .map((column) => {
                                return (
                                    <DropdownMenuCheckboxItem
                                        key={column.id}
                                        className="capitalize"
                                        checked={column.getIsVisible()}
                                        onCheckedChange={(value) =>
                                            column.toggleVisibility(!!value)
                                        }
                                    >
                                        {column.id}
                                    </DropdownMenuCheckboxItem>
                                )
                            })}
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
            <div className="rounded-md border">
                <Table>
                    <TableHeader>
                        {table.getHeaderGroups().map((headerGroup) => (
                            <TableRow key={headerGroup.id}>
                                {headerGroup.headers.map((header, index) => {
                                    const isFirst = index === 0;
                                    const isLast = index === headerGroup.headers.length - 1;
                                    return (
                                        <TableHead
                                            key={header.id}
                                            className={`bg-gray-200 text-grey-900 ${isFirst ? "rounded-tl-sm" : ""
                                                } ${isLast ? "rounded-tr-sm" : ""
                                                }`}
                                        >
                                            {header.isPlaceholder
                                                ? null
                                                : flexRender(
                                                    header.column.columnDef.header,
                                                    header.getContext()
                                                )}
                                        </TableHead>
                                    )
                                })}
                            </TableRow>
                        ))}
                    </TableHeader>
                    <TableBody>
                        {table.getRowModel().rows?.length ? (
                            table.getRowModel().rows.map((row) => (
                                <TableRow
                                    key={row.id}
                                >
                                    {row.getVisibleCells().map((cell) => (
                                        <TableCell key={cell.id} className="border " >
                                            {flexRender(
                                                cell.column.columnDef.cell,
                                                cell.getContext()
                                            )}
                                        </TableCell>
                                    ))}
                                </TableRow>
                            ))
                        ) : (
                            <TableRow>
                                <TableCell
                                    colSpan={columns.length}
                                    className="h-24 text-center"
                                >
                                    No results.
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>
            <div className="flex items-center justify-end space-x-2 py-4">
                <div className="flex-1 text-sm text-muted-foreground">
                    {table.getFilteredSelectedRowModel().rows.length} of{" "}
                    {table.getFilteredRowModel().rows.length} row(s) selected.
                </div>
                <div className="space-x-2">
                    <Button
                        variant="outline"
                        size="sm"
                        onClick={() => table.previousPage()}
                        disabled={!table.getCanPreviousPage()}
                    >
                        Previous
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        onClick={() => table.nextPage()}
                        disabled={!table.getCanNextPage()}
                    >
                        Next
                    </Button>
                </div>
            </div>
        </div>
    )
}
