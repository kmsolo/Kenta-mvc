<?php

declare(strict_types=1);

namespace App\Model;

/**
 * En hand med kort (spelarens eller bankens) i spelet 21.
 * Ansvarar bara för sina egna kort och sitt eget värde -
 * vet inget om motståndaren, kortleken eller spelets regler i övrigt.
 */
final class Hand
{
    /** @var Card[] */
    private array $cards = [];

    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * @param Card[] $cards
     */
    public function setCards(array $cards): void
    {
        $this->cards = $cards;
    }

    /**
     * Summerar handens värde. Ess räknas som 11, men sänks till 1
     * (ett i taget) så länge handen annars skulle spricka.
     */
    public function getValue(): int
    {
        $value = 0;
        $aceCount = 0;

        foreach ($this->cards as $card) {
            $value += $card->getRank()->blackjackValue();

            if ($card->getRank()->isAce()) {
                ++$aceCount;
            }
        }

        while ($value > 21 && $aceCount > 0) {
            $value -= 10; // räkna om ett ess från 11 till 1
            --$aceCount;
        }

        return $value;
    }

    public function isBust(): bool
    {
        return $this->getValue() > 21;
    }

    public function count(): int
    {
        return count($this->cards);
    }
}
