import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import Swal from 'sweetalert2';

import { LandaService } from 'src/app/core/services/landa.service';
import { TransactionService } from '../../services/transaction.service';
import { AuthService } from 'src/app/pages/auth/services/auth.service';

@Component({
    selector: 'transaction-list',
    templateUrl: './transaction-list.component.html',
    styleUrls: ['./transaction-list.component.scss']
})
export class TransactionListComponent implements OnInit {
    dtOptions: DataTables.Settings = {};
    userLogin;
    listTransactions: [];
    titleCard: string;
    modelId: number;
    isOpenForm: boolean = false;

    constructor(
        private transactionService: TransactionService,
        private landaService: LandaService,
        private authService: AuthService,
        private modalService: NgbModal
    ) { }

    ngOnInit(): void {
        this.authService.getProfile().subscribe((user: any) => {
            this.userLogin = user;
        });

        this.getTransaction();
    }

    trackByIndex(index: number): any {
        return index;
    }

    getTransaction() {
        // this.transactionService.getTransactions({
        //     user_login: this.userLogin.akses,
        //     user_id: this.userLogin.id
        // }).subscribe((res: any) => {
        //     this.listTransactions = res.data.list;
        // }, (err: any) => {
        //     console.log(err);
        // });
        this.dtOptions = {
            serverSide: true,
            processing: true,
            ordering: false,
            searching: false,
            pagingType: "full_numbers",
            ajax: (dataTablesParameter: any, callback) => {

                const page = parseInt(dataTablesParameter.start) / parseInt(dataTablesParameter.length) + 1;
                const params = {
                    page: page,
                    offset: dataTablesParameter.start,
                    limit: dataTablesParameter.length,
                    user_login: this.userLogin.akses,
                    user_id: this.userLogin.id
                };
                this.transactionService.getTransactions(params).subscribe((res: any) => {
                    this.listTransactions = res.data.list;
                    // this.totalItems = res.data.totalData;

                    setTimeout(() => {
                        var hidden = document.querySelector('.odd') as HTMLElement;
                        if (this.listTransactions != []) {
                            hidden.style.display = 'none';
                        } else {
                            hidden.style.display = 'block';
                        }
                        //   this.progressService.finishLoading();
                    }, 500);

                    callback({
                        recordsTotal: res.data.meta.total,
                        recordsFiltered: res.data.meta.total,
                        data: []
                    });
                });
            },
        }
    }

    showForm(show) {
        this.isOpenForm = show;
    }

    createTransaction() {
        this.titleCard = 'Add Book';
        this.modelId = 0;
        this.showForm(true);
    }

    updateTransaction(transactioModel) {
        this.titleCard = 'Edit Transaction';
        this.modelId = transactioModel.id;
        this.showForm(true);
    }

    deleteTransaction(userId) {
        Swal.fire({
            title: 'Are you sure ?',
            text: 'Transaction will be deleted',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#34c38f',
            cancelButtonColor: '#f46a6a',
            confirmButtonText: 'Yes, Delete this data !',
        }).then((result) => {
            if (result.value) {
                this.transactionService.deleteTransaction(userId).subscribe((res: any) => {
                    this.landaService.alertSuccess('Success', res.message);
                    this.getTransaction();
                }, err => {
                    console.log(err);
                });
            }
        });
    }

}
