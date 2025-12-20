import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CardapioPublico } from './cardapio-publico';

describe('CardapioPublico', () => {
  let component: CardapioPublico;
  let fixture: ComponentFixture<CardapioPublico>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CardapioPublico]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CardapioPublico);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
