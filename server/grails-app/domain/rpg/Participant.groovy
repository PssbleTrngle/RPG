package rpg

abstract class Participant {

    int health, side
    boolean died = false, joined = true

    abstract int maxHealth();

}
