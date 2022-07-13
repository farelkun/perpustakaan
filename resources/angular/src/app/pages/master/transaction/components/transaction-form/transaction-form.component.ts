import { Component, EventEmitter, Input, OnInit, Output, SimpleChange } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';
import { BookCategoryService } from '../../../book-categories/services/book-category.service';
import { BookService } from '../../../books/services/book.service';
import { UserService } from '../../../users/services/user-service.service';
import { TransactionService } from '../../services/transaction.service';

@Component({
    selector: 'transaction-form',
    templateUrl: './transaction-form.component.html',
    styleUrls: ['./transaction-form.component.scss']
})
export class TransactionFormComponent implements OnInit {
    @Input() id: number;
    @Output() afterSave  = new EventEmitter<boolean>();
    mode: string;
    listCategories: [];
    coverPreview: string;
    formModel : {
        id: number,
        borrow_date: string,
        return_date: string,
        penalty: number,
        is_status: string,
        status: string,
        detail: [
            {
                id: number,
                transaction_id: number,
                book_id: number
            }
        ],
        admin: [
            {
                id: number,
                name: string,
            }
        ],
        user: [
            {
                id: number,
                name: string,
            }
        ],
    };
    listBooks: [];
    listUsers: [];
    listAdmins: [];
    listDetail: any;

    constructor(
        private transactionService: TransactionService,
        private userService: UserService,
        private bookService: BookService,
        private landaService: LandaService
    ) {}

    ngOnInit(): void {
        this.getUsers();
        this.getAdmins();
        this.getBooks();
    }

    ngOnChanges(changes: SimpleChange) {
        this.emptyForm();
    }

    emptyForm() {
        this.mode = 'add';
        this.formModel = {
            id: 0,
            borrow_date: '',
            return_date: '',
            penalty: 0,
            is_status: '',
            status: '',
            detail: [
                {
                    id: 0,
                    transaction_id: 0,
                    book_id: 0
                }
            ],
            admin: [
                {
                    id: 0,
                    name: '',
                }
            ],
            user: [
                {
                    id: 0,
                    name: '',
                }
            ],
        }

        if (this.id > 0) {
            this.mode = 'edit';
            this.getTransaction(this.id);
        }
    }

    save() {
        if(this.mode == 'add') {
            this.transactionService.createTransaction(this.formModel).subscribe((res : any) => {
                this.landaService.alertSuccess('Success', res.message);
                this.afterSave.emit();
            }, err => {
                this.landaService.alertError('Sorry', err.error.errors);
            });
        } else {
            this.transactionService.updateTransaction(this.formModel).subscribe((res : any) => {
                this.landaService.alertSuccess('Berhasil', res.message);
                this.afterSave.emit();
            }, err => {
                this.landaService.alertError('Sorry', err.error.errors);
            });
        }
    }

    getTransaction(transactionId) {
        this.transactionService.getTransactionById(transactionId).subscribe((res: any) => {
            this.formModel = res.data;
            console.log(res.data)
        }, err => {
            console.log(err);
        });
    }

    addDetail() {
        const newDet = {
            id: 0,
            transaction_id: this.formModel.id,
            book_id: 0,
        };
        this.formModel.detail.push(newDet);
    }

    removeDetail(detail, paramIndex) {
        detail.splice(paramIndex, 1);
    }

    trackByIndex(index: number): any {
        return index;
    }

    back() {
        this.afterSave.emit();
    }

    getUsers() {
        this.userService.getUsers({
            role: 2
        }).subscribe((res: any) => {
            this.listUsers = res.data.list;
        }, err => {
            console.log(err);
        })
    }

    getAdmins() {
        this.userService.getUsers({
            role: 1
        }).subscribe((res: any) => {
            this.listAdmins = res.data.list;
        }, err => {
            console.log(err);
        })
    }

    getBooks() {
        this.bookService.getBooks([]).subscribe((res: any) => {
            this.listBooks = res.data.list;
        }, err => {
            console.log(err);
        })
    }
}
