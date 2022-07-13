import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import {
    NgbModule,
    NgbTooltipModule,
    NgbModalModule
} from '@ng-bootstrap/ng-bootstrap';
import { NgSelectModule } from '@ng-select/ng-select';
import { DataTablesModule } from 'angular-datatables';

import { MasterRoutingModule } from './master-routing.module';
import { DaftarUserComponent } from './users/components/daftar-user/daftar-user.component';
import { FormUserComponent } from './users/components/form-user/form-user.component';
import { DaftarRolesComponent } from './roles/components/daftar-roles/daftar-roles.component';
import { FormRolesComponent } from './roles/components/form-roles/form-roles.component';
import { BookCategoryListComponent } from './book-categories/components/book-category-list/book-category-list.component';
import { BookCategoryFormComponent } from './book-categories/components/book-category-form/book-category-form.component';
import { BookFormComponent } from './books/components/book-form/book-form.component';
import { BookListComponent } from './books/components/book-list/book-list.component';
import { TransactionListComponent } from './transaction/components/transaction-list/transaction-list.component';
import { TransactionFormComponent } from './transaction/components/transaction-form/transaction-form.component';


@NgModule({
    declarations: [DaftarUserComponent, FormUserComponent, DaftarRolesComponent, FormRolesComponent, BookCategoryListComponent, BookCategoryFormComponent, BookFormComponent, BookListComponent, TransactionListComponent, TransactionFormComponent],
    imports: [
        CommonModule,
        MasterRoutingModule,
        NgbModule,
        NgbTooltipModule,
        NgbModalModule,
        NgSelectModule,
        FormsModule,
        DataTablesModule
    ]
})
export class MasterModule { }
