# Test implementation for PSR-14 draft

## PSR glitches found while implementing

- An `UnaccetableListenerException` seems to be more than appropriate, as this safety net while looking for a listener should be included in every implementation

- TaskProcessor -> a listener could receive a Task but return a `StoppableTask`, or VICE VERSA. This actually would take away the power for the caller / library author to decide whether it was stopped.

- We might should just take into consideration what people could do when you have something that is extended from a `EventInterface` but does not deal with `TaskInterface` or `MessageInterface`

- `EventErrorInterface` is not hinted in docs anyway - why should I use it?

- `StoppableTaskInterface->isStopped`: Comment states that it is typically only used for dispatcher, which is not the case

### Common issues when developing

- All tasks need to return a `TaskInterface` object - enforce it? Detect it?

- What about `function (MyTask $task) use ($something) { $something->nice(); return $task; }`