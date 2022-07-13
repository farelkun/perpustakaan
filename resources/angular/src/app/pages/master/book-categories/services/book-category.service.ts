import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
    providedIn: 'root'
})
export class BookCategoryService {

    constructor(private landaService: LandaService) { }

    getBookCategories(arrParameter) {
        return this.landaService.DataGet('/v1/book-categories', arrParameter);
    }

    getBookCategoryById(bookCategoryId) {
        return this.landaService.DataGet('/v1/book-categories/' + bookCategoryId);
    }

    createBookCategory(payload) {
        return this.landaService.DataPost('/v1/book-categories', payload);
    }

    updateBookCategory(payload) {
        return this.landaService.DataPut('/v1/book-categories', payload);
    }

    deleteBookCategory(bookCategoryId) {
        return this.landaService.DataDelete('/v1/book-categories/' + bookCategoryId);
    }
}
