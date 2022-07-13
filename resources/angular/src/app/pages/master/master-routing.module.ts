import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { BookCategoryListComponent } from './book-categories/components/book-category-list/book-category-list.component';
import { BookListComponent } from './books/components/book-list/book-list.component';
import { DaftarRolesComponent } from './roles/components/daftar-roles/daftar-roles.component';
import { TransactionListComponent } from './transaction/components/transaction-list/transaction-list.component';
import { DaftarUserComponent } from './users/components/daftar-user/daftar-user.component';

const routes: Routes = [
    { path: 'users', component: DaftarUserComponent },
    { path: 'roles', component: DaftarRolesComponent },
    { path: 'book-categories', component: BookCategoryListComponent },
    { path: 'books', component: BookListComponent },
    { path: 'transactions', component: TransactionListComponent },
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class MasterRoutingModule { }
