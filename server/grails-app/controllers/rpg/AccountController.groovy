package rpg

import grails.core.GrailsApplication
import grails.plugins.*

class AccountController implements PluginManagerAware {

    GrailsApplication grailsApplication
    GrailsPluginManager pluginManager

    def login() {
        def params = request.getJSON()
        String username = params['username'];
        String password = params['password'];
        assert username != null : 'No username given';
        assert password != null : 'No password given';

        Account account = Account::findByName(username);
        assert account != null : 'No account with that username'


    }

    def register() {
        def params = request.getJSON()
        String username = params['username'];
        String password = params['password'];
        assert username != null : 'No username given';
        assert password != null : 'No password given';

        Account existing = Account::findByName(username);
        assert existing == null : 'This username is not available'

        createAccount(username, password);
    }

    Account getAccount() {
        Account existing = Account.first()
        if(existing) return existing
        createAccount('Dev', '1234')
    }

    Account createAccount(String username, String password) {
        assert username != null : 'No username given'
        assert password != null : 'No password given'

        String hash = password
        Account account = new Account(name: username, passwordHash: hash)
        account.save(failOnError: true)

        def clazzes = Clazz.getAll()
        Collections.shuffle(clazzes)
        for(int i = 1; i <= 3; i++)
            createCharacter("Char ${i}", account, clazzes.get(i))

        Account.withTransaction {
            account.save(failOnError: true)
        }
        account
    }

    Character createCharacter(String name, Account account, Clazz clazz) {
        assert name != null : 'No name given'
        assert account != null : 'No account given'
        assert clazz != null : 'No class given'

        Position position = new Position(area: Area.first()).save(failOnError: true)
        Character character = new Character(name: name, position: position)
        character.setHealth(character.maxHealth())
        character.addToClazzes(clazz)

        def r = {int min, int max -> (int) Math.floor(Math.random() * (max-min)) + min}
        Slot bag = Slot.findById('bag')

        for(int i in 0..r(4, 8)) {
            Item item = Item.getAll()[r(0, Item.count() - 1)]
            character.addToInventory(new Stack(amount: r(1, 6), item: item, slot: bag))
        }

        account.addToCharacters(character)

        if(account.getCharacters().size() <= 1) {
            account.setSelected(character)
            Battle.create(character)
        }

        character
    }
}
