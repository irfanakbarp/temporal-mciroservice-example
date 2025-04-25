import React from 'react';
import { UserTable } from './components/UserTable';

const Page = () => {
    const users = [
        { id: 1, name: 'John Doe', email: 'john@example.com' },
        { id: 2, name: 'Jane Smith', email: 'jane@example.com' },
        { id: 3, name: 'Bob Johnson', email: '' },
    ];

    return (
        <div className="container mx-auto p-6">
            <div className="bg-white shadow-lg rounded-lg p-6">
                <div className="border-b pb-2">
                    <h1 className="text-xl font-semibold">Users</h1>
                </div>
                <div className="">
                    <UserTable />
                </div>
            </div>
        </div>
    );
};

export default Page;
