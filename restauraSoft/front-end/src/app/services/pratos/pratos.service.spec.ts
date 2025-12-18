import { TestBed } from '@angular/core/testing';

import { PratosService } from './pratos.service';

describe('PratosService', () => {
  let service: PratosService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(PratosService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
