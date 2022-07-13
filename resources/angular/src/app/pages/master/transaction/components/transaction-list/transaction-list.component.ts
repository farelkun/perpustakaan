import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import Swal from 'sweetalert2';

import { LandaService } from 'src/app/core/services/landa.service';
import { TransactionService } from '../../services/transaction.service';

@Component({
    selector: 'transaction-list',
    templateUrl: './transaction-list.component.html',
    styleUrls: ['./transaction-list.component.scss']
})
export class TransactionListComponent implements OnInit {

    listTransactions: [];
    titleCard: string;
    modelId: number;
    isOpenForm: boolean = false;

    constructor(
        private transactionService: TransactionService,
        private landaService: LandaService,
        private modalService: NgbModal
    ) { }

    ngOnInit(): void {
        this.getTransaction();
    }

    trackByIndex(index: number): any {
        return index;
    }

    getTransaction() {
        this.transactionService.getTransactions([]).subscribe((res: any) => {
            this.listTransactions = res.data.list;
        }, (err: any) => {
            console.log(err);
        });
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
